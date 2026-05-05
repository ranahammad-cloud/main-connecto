<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate(['booking_id' => ['required', 'exists:bookings,id'], 'rating' => ['required', 'integer', 'between:1,5'], 'review' => ['nullable', 'string']]);
        $booking = Booking::with('interviewer.profile')->findOrFail($data['booking_id']);
        abort_unless($booking->interviewee_id === $request->user()->id && $booking->status === 'completed', 403);
        $review = Review::updateOrCreate(['booking_id' => $booking->id], $data);
        $profile = $booking->interviewer->profile;
        if ($profile) {
            $stats = Review::whereHas('booking', fn ($q) => $q->where('interviewer_id', $booking->interviewer_id));
            $profile->update(['rating_average' => round($stats->avg('rating'), 2), 'review_count' => $stats->count()]);
        }
        return response()->json($review, 201);
    }
}
