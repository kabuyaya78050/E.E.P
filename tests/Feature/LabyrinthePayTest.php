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
            'services.labyrinthe.merchant' => 'Orange',
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
            'amount' => '100.00',
            'currency' => 'CDF',
        ]);

        $result = app(LabyrinthePay::class)->pay($donation);

        Http::assertSent(fn (Request $request): bool => $request->url() === 'https://payment.example.test/'
            && $request->method() === 'POST'
            && $request->hasHeader('Authorization', 'Bearer test-api-token')
            && $request->data() === [
                'merchant' => 'Orange',
                'type' => 1,
                'reference' => 'PEN-TEST12345',
                'phone' => '243812345678',
                'amount' => 100.0,
                'currency' => 'CDF',
                'callbackUrl' => route('don.callback', 'PEN-TEST12345'),
            ]
        );

        $this->assertSame(true, $result['success']);
        $this->assertSame('ORDER-123', data_get($result, 'data.orderNumber'));
    }

    public function test_live_mode_sends_the_documented_mobile_payment_request_when_configured(): void
    {
        config([
            'services.labyrinthe.mode' => 'live',
            'services.labyrinthe.token' => 'test-api-token',
            'services.labyrinthe.merchant' => 'Orange',
            'services.labyrinthe.gateway' => 'https://payment.example.test/',
            'services.labyrinthe.check_gateway' => 'https://payment.example.test/check/',
        ]);

        Http::preventStrayRequests();
        Http::fake([
            'https://payment.example.test/' => Http::response([
                'success' => true,
                'data' => ['orderNumber' => 'ORDER-123'],
            ]),
        ]);

        $donation = new Donation([
            'reference' => 'PEN-TEST12345',
            'phone' => '243812345678',
            'amount' => '100.00',
            'currency' => 'CDF',
        ]);

        $result = app(LabyrinthePay::class)->pay($donation);

        Http::assertSent(fn (Request $request): bool => $request->hasHeader('Authorization', 'Bearer test-api-token')
            && $request->data()['merchant'] === 'Orange'
            && $request->data()['type'] === 1
            && $request->data()['amount'] === 100.0
            && $request->data()['currency'] === 'CDF'
            && $request->data()['callbackUrl'] === route('don.callback', 'PEN-TEST12345')
        );
        $this->assertSame(true, $result['success']);
    }

    public function test_missing_gateway_returns_configuration_error_without_sending_request(): void
    {
        config([
            'services.labyrinthe.mode' => 'sandbox',
            'services.labyrinthe.token' => 'test-api-token',
            'services.labyrinthe.merchant' => 'Orange',
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

    public function test_missing_merchant_returns_configuration_error_without_sending_request(): void
    {
        config([
            'services.labyrinthe.mode' => 'sandbox',
            'services.labyrinthe.token' => 'test-api-token',
            'services.labyrinthe.merchant' => null,
            'services.labyrinthe.gateway' => 'https://payment.example.test/',
        ]);

        Http::preventStrayRequests();

        $result = app(LabyrinthePay::class)->pay(new Donation([
            'reference' => 'PEN-TEST12345',
            'phone' => '243812345678',
            'amount' => '100.00',
            'currency' => 'CDF',
        ]));

        Http::assertNothingSent();
        $this->assertSame(false, $result['success']);
        $this->assertStringContainsString('LABYRINTHE_MERCHANT', $result['message']);
    }

    public function test_live_mode_requires_a_check_gateway_before_starting_payment(): void
    {
        config([
            'services.labyrinthe.mode' => 'live',
            'services.labyrinthe.token' => 'test-api-token',
            'services.labyrinthe.merchant' => 'Orange',
            'services.labyrinthe.gateway' => 'https://payment.example.test/',
            'services.labyrinthe.check_gateway' => null,
        ]);

        Http::preventStrayRequests();

        $result = app(LabyrinthePay::class)->pay(new Donation([
            'reference' => 'PEN-TEST12345',
            'phone' => '243812345678',
            'amount' => '100.00',
            'currency' => 'CDF',
        ]));

        Http::assertNothingSent();
        $this->assertSame(false, $result['success']);
        $this->assertStringContainsString('LABYRINTHE_CHECK_GATEWAY', $result['message']);
    }

    public function test_connection_failure_returns_a_dns_and_network_diagnostic(): void
    {
        config([
            'services.labyrinthe.mode' => 'sandbox',
            'services.labyrinthe.token' => 'test-api-token',
            'services.labyrinthe.merchant' => 'Orange',
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
            'services.labyrinthe.merchant' => 'Orange',
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
            && $request->hasHeader('Authorization', 'Bearer test-api-token')
            && $request->data() === [
                'orderNumber' => 'ORDER-123',
            ]
        );

        $this->assertSame(true, $result['success']);
        $this->assertSame(true, app(LabyrinthePay::class)->isPaid($result, $donation));
    }
}
