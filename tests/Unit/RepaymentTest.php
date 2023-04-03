<?php

namespace Tests\Unit;

use App\Models\Loan;
use App\Models\Repayment;
use App\Models\User;
use Tests\TestCase;

class RepaymentTest extends TestCase
{
    private Repayment | null $payment;

    protected function setUp(): void
    {
        parent::setUp();
        $this->payment = new Repayment();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->payment = null;
    }

    public function testPaymentFillAbleFields()
    {
        $expected = [
            'customer_id',
            'loan_id',
            'state',
            'payment_order',
            'repaid_date',
            'amount'
        ];

        $this->assertEquals($expected, $this->payment->getFillable());
    }
}
