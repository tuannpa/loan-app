<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $loan
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(Request $loan)
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'term' => $this->term,
            'repayment_frequency' => $this->repayment_frequency
        ];
    }
}
