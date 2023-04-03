<?php

namespace Tests\Unit;

use App\Models\Loan;
use Tests\TestCase;

class LoanTest extends TestCase
{
    private Loan | null $loan;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loan = new Loan();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->loan = null;
    }

    public function testLoanFillAbleFields()
    {
        $expected = [
            'customer_id',
            'description',
            'state',
            'term',
            'repayment_frequency',
            'amount',
            'approved_by'
        ];

        $this->assertEquals($expected, $this->loan->getFillable());
    }
}
