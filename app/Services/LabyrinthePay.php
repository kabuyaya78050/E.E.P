<?php

namespace App\Services;

use App\Models\Donation;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Client du flux Labyrinthe API documenté.
 */
class LabyrinthePay
{
    private function connectionFailureKind(ConnectionException $exception): string
    {
        $message = strtolower($exception->getMessage());

        return match (true) {
            str_contains($message, 'resolve host'),
            str_contains($message, 'getaddrinfo'),
            str_contains($message, 'curl error 6') => 'dns',
            str_contains($message, 'ssl'),
            str_contains($message, 'certificate'),
            str_contains($message, 'curl error 35'),
            str_contains($message, 'curl error 60') => 'tls',
            str_contains($message, 'timed out'),
            str_contains($message, 'timeout'),
            str_contains($message, 'curl error 28') => 'timeout',
            default => 'network',
        };
    }

    private function request(string $gateway, array $payload): array
    {
        $token = (string) config('services.labyrinthe.token');
        try {
            $response = Http::acceptJson()
                ->withToken($token)
                ->connectTimeout(5)
                ->timeout(20)
                ->post($gateway, $payload);
        } catch (ConnectionException $exception) {
            $failureKind = $this->connectionFailureKind($exception);
            $safeDiagnostic = $token === ''
                ? $exception->getMessage()
                : str_replace($token, '[token masqué]', $exception->getMessage());

            Log::warning('Labyrinthe API connection failed.', [
                'gateway_host' => parse_url($gateway, PHP_URL_HOST),
                'failure_kind' => $failureKind,
                'network_error' => mb_substr($safeDiagnostic, 0, 300),
            ]);

            return [
                'success' => false,
                'message' => match ($failureKind) {
                    'dns' => 'Le nom du serveur Labyrinthe est introuvable. Vérifiez l’adresse de la gateway auprès de Labyrinthe.',
                    'tls' => 'La connexion sécurisée à Labyrinthe a échoué. Vérifiez le certificat TLS du serveur.',
                    'timeout' => 'Labyrinthe n’a pas répondu à temps. Le résultat du paiement est incertain; ne relancez pas cette tentative.',
                    default => 'La connexion à Labyrinthe a échoué. Le résultat du paiement est incertain; ne relancez pas cette tentative.',
                },
                'data' => [],
                'errors' => [
                    'kind' => $failureKind,
                    'outcome_unknown' => in_array($failureKind, ['timeout', 'network'], true),
                ],
            ];
        }

        $data = $response->json();
        if (! is_array($data)) {
            Log::warning('Labyrinthe API returned an unreadable response.', [
                'gateway_host' => parse_url($gateway, PHP_URL_HOST),
                'http_status' => $response->status(),
            ]);

            return [
                'success' => false,
                'message' => 'Labyrinthe a renvoyé une réponse illisible.',
                'data' => [],
                'errors' => [
                    'kind' => 'invalid_response',
                    'http_status' => $response->status(),
                    'outcome_unknown' => true,
                ],
            ];
        }

        if (! $response->successful()) {
            Log::warning('Labyrinthe API returned an HTTP error.', [
                'gateway_host' => parse_url($gateway, PHP_URL_HOST),
                'http_status' => $response->status(),
            ]);

            return [
                'success' => false,
                'message' => match (true) {
                    $response->unauthorized(), $response->forbidden() => 'Labyrinthe a refusé les identifiants API.',
                    $response->tooManyRequests() => 'Labyrinthe limite temporairement les demandes. Réessayez plus tard.',
                    $response->serverError() => 'Le serveur Labyrinthe rencontre une erreur. Le résultat du paiement est incertain; ne relancez pas cette tentative.',
                    default => 'Labyrinthe a refusé la demande. Vérifiez les paramètres transmis.',
                },
                'data' => [],
                'errors' => [
                    'kind' => 'http',
                    'http_status' => $response->status(),
                    'outcome_unknown' => $response->serverError(),
                ],
            ];
        }

        $providerSuccess = in_array($data['success'] ?? false, [true, 1, '1'], true);
        $details = is_array($data['data'] ?? null) ? $data['data'] : $data;
        $message = $data['message'] ?? ($providerSuccess ? 'Demande transmise à Labyrinthe.' : 'Labyrinthe a refusé la transaction.');
        if (is_string($message) && $token !== '') {
            $message = str_replace($token, '[token masqué]', $message);
        }

        return [
            'success' => $providerSuccess,
            'message' => $message,
            'data' => $this->redactSensitiveFields($details, $token),
            'errors' => $this->redactSensitiveFields($data['errors'] ?? [], $token),
        ];
    }

    private function redactSensitiveFields(mixed $value, string $token): mixed
    {
        if (is_string($value) && $token !== '') {
            return str_replace($token, '[token masqué]', $value);
        }

        if (! is_array($value)) {
            return $value;
        }

        $redacted = [];
        foreach ($value as $key => $item) {
            $redacted[$key] = is_string($key) && preg_match('/token|authorization|password|secret/i', $key)
                ? '[masqué]'
                : $this->redactSensitiveFields($item, $token);
        }

        return $redacted;
    }

    public function pay(Donation $donation): array
    {
        if (blank(config('services.labyrinthe.token'))) {
            return [
                'success' => false,
                'message' => 'Configuration Labyrinthe incomplète : renseignez LABYRINTHE_TOKEN dans .env.',
            ];
        }

        if (blank(config('services.labyrinthe.merchant'))) {
            return [
                'success' => false,
                'message' => 'Configuration Labyrinthe incomplète : renseignez LABYRINTHE_MERCHANT avec le code marchand fourni par FlexPay.',
            ];
        }

        if (blank(config('services.labyrinthe.gateway'))) {
            return [
                'success' => false,
                'message' => 'Configuration Labyrinthe incomplète : renseignez LABYRINTHE_GATEWAY dans .env.',
            ];
        }

        $mode = config('services.labyrinthe.mode');
        if (! in_array($mode, ['sandbox', 'live'], true)) {
            return [
                'success' => false,
                'message' => 'Configuration Labyrinthe invalide : LABYRINTHE_MODE doit être sandbox ou live.',
            ];
        }

        if ($mode === 'live' && blank(config('services.labyrinthe.check_gateway'))) {
            return [
                'success' => false,
                'message' => 'Configuration live incomplète : renseignez LABYRINTHE_CHECK_GATEWAY avant d’activer les paiements.',
            ];
        }

        return $this->request(config('services.labyrinthe.gateway'), [
            'merchant' => config('services.labyrinthe.merchant'),
            'type' => 1,
            'reference' => $donation->reference,
            'phone' => $donation->phone,
            'amount' => (float) $donation->amount,
            'currency' => $donation->currency,
            'callbackUrl' => route('don.callback', ['reference' => $donation->reference]),
        ]);
    }

    public function check(Donation $donation): array
    {
        if (blank(config('services.labyrinthe.token'))) {
            return [
                'success' => false,
                'message' => 'Configuration Labyrinthe incomplète : renseignez LABYRINTHE_TOKEN dans .env.',
            ];
        }

        if (blank(config('services.labyrinthe.check_gateway'))) {
            return [
                'success' => false,
                'message' => 'Renseignez LABYRINTHE_CHECK_GATEWAY avec l’URL de vérification fournie par Labyrinthe.',
            ];
        }

        if (blank($donation->order_number)) {
            return [
                'success' => false,
                'message' => 'La transaction ne contient pas encore de numéro de commande Labyrinthe.',
            ];
        }

        return $this->request(config('services.labyrinthe.check_gateway'), [
            'orderNumber' => $donation->order_number,
        ]);
    }

    public function isPaid(array $response, Donation $donation): bool
    {
        $details = $response['data'] ?? [];
        if (! is_array($details)
            || ! in_array($response['success'] ?? false, [true, 1, '1'], true)
            || ! in_array($details['code'] ?? null, [0, '0', false], true)
            || ! is_string($details['orderNumber'] ?? null)
            || ! hash_equals((string) $donation->order_number, $details['orderNumber'])) {
            return false;
        }

        if (isset($details['reference']) && ! hash_equals((string) $donation->reference, (string) $details['reference'])) {
            return false;
        }

        if (isset($details['currency']) && strtoupper((string) $donation->currency) !== strtoupper((string) $details['currency'])) {
            return false;
        }

        if (isset($details['amount']) && number_format((float) $donation->amount, 2, '.', '') !== number_format((float) $details['amount'], 2, '.', '')) {
            return false;
        }

        if (isset($details['phone']) && preg_replace('/\D+/', '', (string) $donation->phone) !== preg_replace('/\D+/', '', (string) $details['phone'])) {
            return false;
        }

        return true;
    }

    public function callbackMatchesDonation(array $callback, Donation $donation): bool
    {
        return isset($callback['reference'], $callback['orderNumber'], $callback['amount'], $callback['currency'], $callback['phone'])
            && hash_equals((string) $donation->reference, (string) $callback['reference'])
            && (string) $donation->currency === (string) $callback['currency']
            && number_format((float) $donation->amount, 2, '.', '') === number_format((float) $callback['amount'], 2, '.', '')
            && preg_replace('/\D+/', '', (string) $donation->phone) === preg_replace('/\D+/', '', (string) $callback['phone']);
    }
}
