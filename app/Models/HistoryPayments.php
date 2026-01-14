<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPayments extends Model
{

    use HasFactory;

    protected $fillable=['user_id','name','payment','group','date','type_of_money','is_reversed','reversed_by_id'];

    protected $casts = [
        'is_reversed' => 'boolean',
    ];

    // Primary relation to the payer (student user)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Backward-compatible alias used in some views/code
    public function student()
    {
        return $this->user();
    }

    // Relationship to the reversal payment (if this payment was reversed)
    public function reversalPayment()
    {
        return $this->belongsTo(HistoryPayments::class, 'reversed_by_id');
    }

    // Relationship to payments reversed by this one (reverse relationship)
    public function reversedPayments()
    {
        return $this->hasMany(HistoryPayments::class, 'reversed_by_id');
    }

}
