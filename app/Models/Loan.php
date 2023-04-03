<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    const PENDING = 'pending';
    const APPROVED = 'approved';
    const PAID = 'paid';

    // Used for mass assignment.
    protected $fillable = [
        'customer_id',
        'description',
        'state',
        'term',
        'repayment_frequency',
        'amount',
        'approved_by'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function payment()
    {
        return $this->hasOne(Repayment::class);
    }
}
