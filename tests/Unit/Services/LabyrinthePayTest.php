<?php

namespace Tests\Unit\Services;

use App\Models\Donation;
use App\Services\LabyrinthePay;
use PHPUnit\Framework\TestCase;

class LabyrinthePayTest extends TestCase
{
    public function test_marks_integer_success_response_with_zero_code_as_paid(): void
    {
        $pay = new LabyrinthePay;
        $donation = new Donation([
            'reference' => 'PEN-TEST12345',
            'order_number' => 'ORDER-123',
            'amount' => '100.00',
            'currency' => 'CDF',
            'phone' => '243812345678',
        ]);

        $isPaid = $pay->isPaid([
            'success' => true,
            'data' => [
                'code' => 0,
                'orderNumber' => 'ORDER-123',
                'reference' => 'PEN-TEST12345',
                'amount' => '100.00',
                'currency' => 'CDF',
                'phone' => '243812345678',
            ],
        ], $donation);

        $this->assertSame(true, $isPaid);
    }

    public function test_does_not_mark_failed_response_as_paid(): void
    {
        $pay = new LabyrinthePay;
        $donation = new Donation([
            'reference' => 'PEN-TEST12345',
            'order_number' => 'ORDER-123',
        ]);

        $isPaid = $pay->isPaid([
            'success' => true,
            'data' => [
                'code' => 1,
                'orderNumber' => 'ORDER-123',
            ],
        ], $donation);

        $this->assertSame(false, $isPaid);
    }

    public function test_does_not_confirm_a_transaction_for_another_order(): void
    {
        $pay = new LabyrinthePay;
        $donation = new Donation([
            'reference' => 'PEN-TEST12345',
            'order_number' => 'ORDER-123',
        ]);

        $isPaid = $pay->isPaid([
            'success' => true,
            'data' => [
                'code' => 0,
                'orderNumber' => 'ORDER-OTHER',
                'reference' => 'PEN-TEST12345',
            ],
        ], $donation);

        $this->assertSame(false, $isPaid);
    }
}
