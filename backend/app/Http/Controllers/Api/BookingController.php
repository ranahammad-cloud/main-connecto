<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        return Booking::with(['interviewer.profile', 'interviewee.profile', 'payment'])
            ->when($user->role === 'interviewer', fn ($q) => $q->where('interviewer_id', $user->id))
            ->when($user->role === 'interviewee', fn ($q) => $q->where('interviewee_id', $user->id))
            ->latest('scheduled_at')->paginate(15);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['interviewer_id' => ['required', 'exists:users,id'], 'scheduled_at' => ['required', 'date', 'after:now'], 'duration_minutes' => ['nullable', 'integer', 'between:30,90']]);
        $interviewer = User::with('profile')->findOrFail($data['interviewer_id']);
        abort_unless($interviewer->role === 'interviewer' && $interviewer->status === 'active', 422, 'Interviewer is unavailable.');
        $booking = Booking::create($data + [
            'interviewee_id' => $request->user()->id,
            'price' => $interviewer->profile?->pricing ?? 25,
            'status' => 'requested',
            'video_room_id' => 'connecto-'.Str::uuid(),
        ]);
        return response()->json($booking->load('interviewer.profile'), 201);
    }

    public function show(Booking $booking) { $this->authorizeBooking($booking); return $booking->load(['interviewer.profile', 'interviewee.profile', 'payment', 'review']); }
    public function update(Request $request, Booking $booking) { $this->authorizeBooking($booking); $booking->update($request->validate(['scheduled_at' => ['sometimes', 'date', 'after:now'], 'status' => ['sometimes', 'in:cancelled,disputed,in_session,completed']])); return $booking; }
    public function destroy(Booking $booking) { $this->authorizeBooking($booking); $booking->update(['status' => 'cancelled']); return response()->noContent(); }
    public function accept(Request $request, Booking $booking) { abort_unless($booking->interviewer_id === $request->user()->id, 403); $booking->update(['status' => 'accepted']); return $booking; }
    public function reject(Request $request, Booking $booking) { abort_unless($booking->interviewer_id === $request->user()->id, 403); $booking->update(['status' => 'rejected']); return $booking; }
    public function feedback(Request $request, Booking $booking) { abort_unless($booking->interviewer_id === $request->user()->id, 403); $booking->update($request->validate(['feedback' => ['required', 'string']]) + ['status' => 'completed']); return $booking; }
    private function authorizeBooking(Booking $booking): void { abort_unless(in_array(request()->user()->id, [$booking->interviewer_id, $booking->interviewee_id]) || request()->user()->isAdmin(), 403); }
}
