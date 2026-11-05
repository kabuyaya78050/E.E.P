<?php

namespace Tests\Feature;

use App\Models\Donation;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class DonationPaymentSubmissionTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_sandbox_donation_form_warns_that_it_does_not_collect_real_payments(): void
    {
        config(['services.labyrinthe.mode' => 'sandbox']);
        $submissionKey = (string) Str::uuid();
        $this->withSession(['donation_submission_key' => $submissionKey]);

        $response = $this->get(route('don.create'));

        $response->assertOk();
        $response->assertSee('name="submission_key"', false);
        $response->assertSee($submissionKey);
        $response->assertSee('aucun paiement réel ne sera prélevé');
        $this->assertSame(1, substr_count($response->getContent(), 'name="submission_key"'));
        $response->assertSeeInOrder([
            '<form class="form" method="POST" action="'.route('don.store').'"',
            'name="_token"',
            'name="submission_key"',
            'name="name"',
        ], false);
    }

    public function test_repeated_form_submission_sends_only_one_labyrinthe_request(): void
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
                'data' => ['orderNumber' => 'ORDER-123'],
            ]),
        ]);

        $submissionKey = (string) Str::uuid();
        $this->withSession(['donation_submission_key' => $submissionKey]);
        $payload = [
            'name' => 'Test Donor',
            'email' => 'donor@example.test',
            'phone' => '243812345678',
            'amount' => '100.00',
            'currency' => 'CDF',
            'purpose' => 'offrande',
            'submission_key' => $submissionKey,
        ];

        $firstResponse = $this->post(route('don.store'), $payload);
        $donation = Donation::where('submission_key', $submissionKey)->firstOrFail();
        $secondResponse = $this->post(route('don.store'), $payload);

        $firstResponse->assertRedirect(route('don.status', $donation));
        $secondResponse->assertRedirect(route('don.status', $donation));
        $this->assertDatabaseCount('donations', 1);
        Http::assertSentCount(1);
    }

    public function test_unverified_failure_callback_does_not_change_donation_status(): void
    {
        config([
            'services.labyrinthe.token' => 'test-api-token',
            'services.labyrinthe.check_gateway' => 'https://payment.example.test/check/',
        ]);

        Http::preventStrayRequests();
        Http::fake([
            'https://payment.example.test/check/' => Http::response([
                'success' => false,
                'message' => 'Still pending',
                'data' => ['code' => '1', 'orderNumber' => 'ORDER-123'],
            ]),
        ]);

        $donation = Donation::create([
            'reference' => 'PEN-CALLBACK123',
            'name' => 'Test Donor',
            'phone' => '243812345678',
            'amount' => '100.00',
            'currency' => 'CDF',
            'purpose' => 'offrande',
            'status' => 'pending',
        ]);

        $response = $this->post(route('don.callback', $donation), [
            'code' => '1',
            'reference' => $donation->reference,
            'provider_reference' => 'PROVIDER-123',
            'orderNumber' => 'ORDER-123',
            'amount' => '100.00',
            'amountCustomer' => '100.00',
            'phone' => $donation->phone,
            'currency' => $donation->currency,
            'createdAt' => '2026-11-02T00:00:00Z',
            'channel' => 'mobile',
        ]);

        $response->assertServiceUnavailable();
        $this->assertDatabaseHas('donations', [
            'reference' => $donation->reference,
            'status' => 'pending',
            'order_number' => null,
        ]);
        Http::assertSentCount(1);
    }
}
