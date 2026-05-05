<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_INTERVIEWEE = 'interviewee';
    public const ROLE_INTERVIEWER = 'interviewer';
    public const ROLE_ADMIN = 'admin';

    protected $fillable = ['name', 'email', 'password', 'role', 'auth_provider', 'status'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime', 'password' => 'hashed'];

    public function profile() { return $this->hasOne(Profile::class); }
    public function wallet() { return $this->hasOne(Wallet::class); }
    public function interviewerBookings() { return $this->hasMany(Booking::class, 'interviewer_id'); }
    public function intervieweeBookings() { return $this->hasMany(Booking::class, 'interviewee_id'); }
    public function isAdmin(): bool { return $this->role === self::ROLE_ADMIN; }
}
