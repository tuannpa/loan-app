<?php

namespace App\Interfaces;

interface LoanRepositoryInterface
{
    public function createLoan($loanData);
    public function addRepayment($repaymentData, $id);
}
