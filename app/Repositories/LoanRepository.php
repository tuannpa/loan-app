<?php

namespace App\Repositories;

use App\Interfaces\LoanRepositoryInterface;
use App\Models\User;
use App\Models\Loan;
use App\Models\Repayment;
use Illuminate\Support\Facades\DB;

class LoanRepository implements LoanRepositoryInterface
{
    /**
     * @param $loanData
     * @return Loan
     * @throws \Exception
     */
    public function createLoan($loanData)
    {
        /** @var User $customer */
        $customer = auth()->user();
        if (empty($loanData['repayment_frequency'])) {
            $loanData['repayment_frequency'] = 'weekly';
        }
        $loanData['state'] = Loan::PENDING;

        try {
            DB::beginTransaction();

            // Create loan
            /** @var Loan $loan */
            $loan = new Loan();
            foreach ($loanData as $k => $v) {
                $loan->{$k} = $v;
            }
            $loan->customer()->associate($customer);
            $loan->save();

            // Create repayments based on loan data
            $repayments = [];
            $frequency = $loanData['repayment_frequency'] === 'weekly' ? 7 : 30;
            for ($i = 1; $i <= $loanData['term']; $i++) {
                $repayments[] = [
                    'customer_id' => $customer->id,
                    'loan_id' => $loan->id,
                    'state' => Repayment::PENDING,
                    'payment_order' => $i,
                    'repaid_date' => $loan->created_at->addDays($frequency * $i),
                    'amount' => $loanData['amount'] / $loanData['term']
                ];
            }
            Repayment::insert($repayments);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $loan;
    }

    public function addRepayment($repaymentData, $id)
    {
        $repayment = auth()->user()->repayments()->findOrFail($id);

        if ($repaymentData['amount'] < $repayment->amount) {
            throw new \Exception('Repayment amount should be equal to reschedule amount');
        }

        $repayment->state = Repayment::PAID;
        $repayment->save();

        $paidSchedules = 0;
        foreach (auth()->user()->repayments as $repay) {
            if (Repayment::PAID === $repay->state) {
                $paidSchedules += 1;
            }
        }

        if ($paidSchedules === auth()->user()->repayments()->where('loan_id', $repayment->loan->id)->count()) {
            $repayment->loan()->update([
                'state' => Loan::PAID
            ]);
        }

        return $repayment;
    }
}
