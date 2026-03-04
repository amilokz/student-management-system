<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'fee_type',
        'amount',
        'due_date',
        'paid_date',
        'status',
        'paid_amount',
        'payment_method',
        'transaction_id',
        'notes'
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function getBalanceAttribute()
    {
        return $this->amount - $this->paid_amount;
    }

    public function getIsOverdueAttribute()
    {
        return $this->status !== 'paid' && $this->due_date < now();
    }
}