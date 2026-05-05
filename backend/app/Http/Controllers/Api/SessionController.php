<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class SessionController extends Controller
{
    public function show(Booking $booking)
    {
        abort_unless(in_array(request()->user()->id, [$booking->interviewer_id, $booking->interviewee_id]) || request()->user()->isAdmin(), 403);
        return ['provider' => config('services.video.provider', 'agora'), 'room_id' => $booking->video_room_id, 'app_id' => config('services.agora.app_id'), 'expires_in' => 3600];
    }
}
