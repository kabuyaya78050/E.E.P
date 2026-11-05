<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Services\LabyrinthePay;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    public function create(Request $request)
    {
        $submissionKey = $request->session()->get('donation_submission_key');
        if ($submissionKey && Donation::where('submission_key', $submissionKey)->where('status', 'paid')->exists()) {
            $request->session()->forget('donation_submission_key');
            $submissionKey = null;
        }

        $submissionKey ??= (string) Str::uuid();
        $request->session()->put('donation_submission_key', $submissionKey);

        return view('donate.create', ['submissionKey' => $submissionKey]);
    }

    public function store(Request $request, LabyrinthePay $pay)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['required', 'regex:/^\+?[0-9]{9,15}$/'],
            'amount' => ['required', 'numeric', 'min:1', 'max:1000000'],
            'currency' => ['required', 'in:USD,CDF'],
            'purpose' => ['required', 'in:'.implode(',', array_keys(Donation::PURPOSES))],
            'submission_key' => ['required', 'uuid'],
        ], [
            'phone.regex' => 'Saisissez un numéro valide, avec ou sans indicatif (ex. 243812345678).',
        ]);

        $sessionSubmissionKey = (string) $request->session()->get('donation_submission_key', '');
        abort_unless($sessionSubmissionKey !== '' && hash_equals($sessionSubmissionKey, $data['submission_key']), 419);

        $existingDonation = Donation::where('submission_key', $data['submission_key'])->first();
        if ($existingDonation) {
            return redirect()->route('don.status', $existingDonation);
        }

        $data['phone'] = ltrim($data['phone'], '+');
        $submissionKey = $data['submission_key'];
        unset($data['submission_key']);

        try {
            $donation = Donation::create($data + [
                'reference' => 'PEN-'.strtoupper(Str::random(10)),
                'submission_key' => $submissionKey,
                'status' => 'pending',
            ]);
        } catch (QueryException $exception) {
            $existingDonation = Donation::where('submission_key', $submissionKey)->first();
            if ($existingDonation) {
                return redirect()->route('don.status', $existingDonation);
            }

            throw $exception;
        }

        try {
            $result = $pay->pay($donation);
        } catch (\Throwable $e) {
            Log::error('Labyrinthe payment launch failed unexpectedly.', [
                'donation_reference' => $donation->reference,
                'exception_class' => $e::class,
            ]);
            $result = [
                'success' => false,
                'message' => 'Le lancement du paiement a échoué de manière inattendue. Contactez le support avant de réessayer.',
                'errors' => ['outcome_unknown' => true],
            ];
        }

        $accepted = in_array($result['success'] ?? false, [true, 1, '1'], true);
        $outcomeUnknown = data_get($result, 'errors.outcome_unknown') === true;
        $donation->update([
            'order_number' => data_get($result, 'data.orderNumber'),
            'gateway_response' => $result,
            'status' => $accepted || $outcomeUnknown ? 'pending' : 'failed',
        ]);

        if (! $accepted && ! $outcomeUnknown) {
            $request->session()->forget('donation_submission_key');
        }

        return redirect()->route('don.status', $donation);
    }

    public function status(Donation $donation)
    {
        return view('donate.status', ['donation' => $donation]);
    }

    public function verify(Request $request, Donation $donation, LabyrinthePay $pay)
    {
        if ($donation->status === 'pending' && $donation->order_number) {
            try {
                $result = $pay->check($donation);
                if ($pay->isPaid($result, $donation)) {
                    $donation->update(['status' => 'paid', 'gateway_response' => $result]);
                    $this->forgetCompletedSubmissionKey($request, $donation);
                } else {
                    $donation->update(['gateway_response' => $result]);
                }
            } catch (\Throwable $e) {
                Log::error('Labyrinthe transaction verification failed unexpectedly.', [
                    'donation_reference' => $donation->reference,
                    'exception_class' => $e::class,
                ]);
            }
        }

        return redirect()->route('don.status', $donation);
    }

    public function callback(Request $request, string $reference, LabyrinthePay $pay)
    {
        $data = $request->validate([
            'code' => ['required'],
            'reference' => ['required', 'string', 'max:255'],
            'provider_reference' => ['required', 'string', 'max:255'],
            'orderNumber' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'amountCustomer' => ['required', 'numeric', 'min:0'],
            'phone' => ['required', 'string', 'max:30'],
            'currency' => ['required', 'in:USD,CDF'],
            'createdAt' => ['required', 'string', 'max:100'],
            'channel' => ['required', 'string', 'max:100'],
        ]);

        $donation = Donation::where('reference', $reference)->firstOrFail();
        abort_unless($pay->callbackMatchesDonation($data, $donation), 422);

        if ($donation->status === 'paid') {
            return response()->noContent();
        }

        if ($donation->order_number && ! hash_equals($donation->order_number, $data['orderNumber'])) {
            return response()->noContent(409);
        }

        $donation->order_number = $data['orderNumber'];

        try {
            $result = $pay->check($donation);
        } catch (\Throwable $e) {
            Log::error('Labyrinthe callback verification failed unexpectedly.', [
                'donation_reference' => $donation->reference,
                'exception_class' => $e::class,
            ]);

            return response()->json(['message' => 'Transaction verification is temporarily unavailable.'], 503);
        }

        if ($pay->isPaid($result, $donation)) {
            $donation->update(['status' => 'paid', 'gateway_response' => $result]);
            $this->forgetCompletedSubmissionKey($request, $donation);

            return response()->noContent();
        }

        return response()->json(['message' => 'Transaction verification is pending.'], 503);
    }

    private function forgetCompletedSubmissionKey(Request $request, Donation $donation): void
    {
        if ($request->session()->get('donation_submission_key') === $donation->submission_key) {
            $request->session()->forget('donation_submission_key');
        }
    }
}
