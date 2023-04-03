<?php

namespace App\Repositories;

use App\Interfaces\AdminRepositoryInterface;
use App\Models\Loan;

class AdminRepository implements AdminRepositoryInterface
{
    /**
     * @param $loanId
     * @return mixed
     * @throws \Exception
     */
    public function approveLoan($loanId)
    {
        $loan = Loan::findOrFail(intval($loanId));

        if (Loan::PENDING === $loan->state) {
            $loan->state = Loan::APPROVED;
            $loan->save();
        } else {
            throw new \Exception('Only pending loans can be updated');
        }

        return $loan;
    }
}
