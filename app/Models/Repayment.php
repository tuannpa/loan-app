<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    use HasFactory;

    const PENDING = 'pending';
    const PAID = 'paid';

    // Used for mass assignment.
    protected $fillable = [
        'customer_id',
        'loan_id',
        'state',
        'payment_order',
        'repaid_date',
        'amount'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
