<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Services\LabyrinthePay;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LabyrinthePayTest extends TestCase
{
    public function test_sandbox_payment_sends_documented_fields_without_a_merchant(): void
    {
        config([
            'services.labyrinthe.mode' => 'sandbox',
            'services.labyrinthe.token' => 'test-api-token',
            'services.labyrinthe.gateway' => 'https://payment.example.test/',
        ]);

        Http::preventStrayRequests();
        Http::fake([
            'https://payment.example.test/' => Http::response([
                'success' => true,
                'message' => 'Sandbox accepted',
                'orderNumber' => 'ORDER-123',
            ]),
        ]);

        $donation = new Donation([
            'reference' => 'PEN-TEST12345',
            'phone' => '243812345678',
        ]);

        $result = app(LabyrinthePay::class)->pay($donation);

        Http::assertSent(fn (Request $request): bool => $request->url() === 'https://payment.example.test/'
            && $request->method() === 'POST'
            && $request->hasHeader('token', 'test-api-token')
            && $request->data() === [
                'token' => 'test-api-token',
                'reference' => 'PEN-TEST12345',
                'phone' => '243812345678',
            ]
        );

        $this->assertSame(true, $result['success']);
        $this->assertSame('ORDER-123', data_get($result, 'data.orderNumber'));
    }

    public function test_live_mode_does_not_send_an_undocumented_payment_request(): void
    {
        config([
            'services.labyrinthe.mode' => 'live',
            'services.labyrinthe.token' => 'test-api-token',
        ]);

        Http::preventStrayRequests();

        $donation = new Donation([
            'reference' => 'PEN-TEST12345',
            'phone' => '243812345678',
        ]);

        $result = app(LabyrinthePay::class)->pay($donation);

        Http::assertNothingSent();
        $this->assertSame(false, $result['success']);
        $this->assertStringContainsString('mode sandbox', $result['message']);
    }

    public function test_missing_gateway_returns_configuration_error_without_sending_request(): void
    {
        config([
            'services.labyrinthe.mode' => 'sandbox',
            'services.labyrinthe.token' => 'test-api-token',
            'services.labyrinthe.gateway' => null,
        ]);

        Http::preventStrayRequests();

        $donation = new Donation([
            'reference' => 'PEN-TEST12345',
            'phone' => '243812345678',
        ]);

        $result = app(LabyrinthePay::class)->pay($donation);

        Http::assertNothingSent();
        $this->assertSame(false, $result['success']);
        $this->assertStringContainsString('LABYRINTHE_GATEWAY', $result['message']);
    }

    public function test_connection_failure_returns_a_dns_and_network_diagnostic(): void
    {
        config([
            'services.labyrinthe.mode' => 'sandbox',
            'services.labyrinthe.token' => 'test-api-token',
            'services.labyrinthe.gateway' => 'https://payment.example.test/',
        ]);

        Http::preventStrayRequests();
        Http::fake([
            'https://payment.example.test/' => fn () => throw new ConnectionException('cURL error 6: Could not resolve host'),
        ]);

        $donation = new Donation([
            'reference' => 'PEN-TEST12345',
            'phone' => '243812345678',
        ]);

        $result = app(LabyrinthePay::class)->pay($donation);

        Http::assertNothingSent();
        $this->assertSame(false, $result['success']);
        $this->assertStringContainsString('nom du serveur', $result['message']);
    }

    public function test_transaction_check_posts_token_and_order_number_to_check_gateway(): void
    {
        config([
            'services.labyrinthe.token' => 'test-api-token',
            'services.labyrinthe.check_gateway' => 'https://payment.example.test/check/',
        ]);

        Http::preventStrayRequests();
        Http::fake([
            'https://payment.example.test/check/' => Http::response([
                'success' => true,
                'message' => 'Paid',
                'data' => [
                    'code' => '0',
                    'orderNumber' => 'ORDER-123',
                    'reference' => 'PEN-TEST12345',
                    'amount' => '100.00',
                    'currency' => 'CDF',
                    'phone' => '243812345678',
                ],
            ]),
        ]);

        $donation = new Donation([
            'reference' => 'PEN-TEST12345',
            'order_number' => 'ORDER-123',
            'amount' => '100.00',
            'currency' => 'CDF',
            'phone' => '243812345678',
        ]);

        $result = app(LabyrinthePay::class)->check($donation);

        Http::assertSent(fn (Request $request): bool => $request->url() === 'https://payment.example.test/check/'
            && $request->method() === 'POST'
            && $request->hasHeader('token', 'test-api-token')
            && $request->data() === [
                'token' => 'test-api-token',
                'orderNumber' => 'ORDER-123',
            ]
        );

        $this->assertSame(true, $result['success']);
        $this->assertSame(true, app(LabyrinthePay::class)->isPaid($result, $donation));
    }
}
