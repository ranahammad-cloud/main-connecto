<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function createIntent(Request $request, Booking $booking)
    {
        abort_unless($booking->interviewee_id === $request->user()->id, 403);
        $commission = round($booking->price * 0.10, 2);
        $payment = Payment::updateOrCreate(['booking_id' => $booking->id], ['amount' => $booking->price, 'commission' => $commission, 'interviewer_payout' => $booking->price - $commission, 'status' => 'requires_payment']);
        Stripe::setApiKey(config('services.stripe.secret'));
        $intent = PaymentIntent::create(['amount' => (int) round($booking->price * 100), 'currency' => 'usd', 'metadata' => ['booking_id' => $booking->id], 'automatic_payment_methods' => ['enabled' => true]]);
        $payment->update(['stripe_payment_intent_id' => $intent->id]);
        return ['client_secret' => $intent->client_secret, 'payment' => $payment];
    }

    public function webhook(Request $request)
    {
        $payload = $request->all();
        if (($payload['type'] ?? '') === 'payment_intent.succeeded') {
            $intent = $payload['data']['object'];
            $payment = Payment::where('stripe_payment_intent_id', $intent['id'])->first();
            if ($payment) { $payment->update(['status' => 'held_in_escrow']); $payment->booking()->update(['status' => 'paid']); }
        }
        return response()->json(['received' => true]);
    }
}
