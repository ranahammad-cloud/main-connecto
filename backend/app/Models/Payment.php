<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['booking_id','amount','commission','interviewer_payout','status','stripe_payment_intent_id','released_at'];
    protected $casts = ['amount' => 'decimal:2', 'commission' => 'decimal:2', 'interviewer_payout' => 'decimal:2', 'released_at' => 'datetime'];
    public function booking() { return $this->belongsTo(Booking::class); }
}
