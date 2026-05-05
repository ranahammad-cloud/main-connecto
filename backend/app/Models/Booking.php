<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['interviewer_id','interviewee_id','scheduled_at','duration_minutes','status','price','video_room_id','chat_transcript','feedback','dispute_status'];
    protected $casts = ['scheduled_at' => 'datetime', 'price' => 'decimal:2', 'chat_transcript' => 'array'];
    public function interviewer() { return $this->belongsTo(User::class, 'interviewer_id'); }
    public function interviewee() { return $this->belongsTo(User::class, 'interviewee_id'); }
    public function payment() { return $this->hasOne(Payment::class); }
    public function review() { return $this->hasOne(Review::class); }
}
