<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['user_id','balance','transactions'];
    protected $casts = ['balance' => 'decimal:2', 'transactions' => 'array'];
    public function user() { return $this->belongsTo(User::class); }
}
