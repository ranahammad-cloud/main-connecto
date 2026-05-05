<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users() { return User::with('profile')->latest()->paginate(25); }
    public function approveInterviewer(User $user) { abort_unless($user->role === 'interviewer', 422); $user->update(['status' => 'active']); return $user; }
    public function bookings() { return Booking::with(['interviewer', 'interviewee', 'payment'])->latest()->paginate(25); }
    public function transactions() { return Payment::with('booking')->latest()->paginate(25); }
    public function resolveDispute(Request $request, Booking $booking) { $booking->update(['dispute_status' => 'resolved', 'status' => $request->input('status', 'completed')]); return $booking; }
}
