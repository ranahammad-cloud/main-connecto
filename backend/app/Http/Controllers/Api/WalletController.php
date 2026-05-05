<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function show(Request $request) { return $request->user()->wallet()->firstOrCreate(['user_id' => $request->user()->id], ['balance' => 0, 'transactions' => []]); }
    public function withdraw(Request $request) { $data = $request->validate(['amount' => ['required', 'numeric', 'min:5']]); $wallet = $this->show($request); abort_if($wallet->balance < $data['amount'], 422, 'Insufficient balance.'); $wallet->update(['balance' => $wallet->balance - $data['amount'], 'transactions' => array_merge($wallet->transactions ?? [], [['type' => 'withdrawal_requested', 'amount' => $data['amount'], 'at' => now()]])]); return $wallet; }
}
