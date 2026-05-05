<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        return User::query()->where('role', 'interviewer')->where('status', 'active')->with('profile')
            ->when($request->search, fn ($q, $s) => $q->where(fn ($x) => $x->where('name', 'like', "%$s%")->orWhereHas('profile', fn ($p) => $p->where('bio', 'like', "%$s%")->orWhere('target_role', 'like', "%$s%"))))
            ->when($request->skill, fn ($q, $s) => $q->whereHas('profile', fn ($p) => $p->whereJsonContains('skills', $s)))
            ->when($request->max_price, fn ($q, $p) => $q->whereHas('profile', fn ($x) => $x->where('pricing', '<=', $p)))
            ->paginate(12);
    }
    public function show(User $user) { abort_unless($user->role === 'interviewer', 404); return $user->load('profile', 'interviewerBookings.review'); }
}
