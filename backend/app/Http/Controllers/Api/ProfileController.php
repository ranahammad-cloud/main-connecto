<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Profile $profile) { return $profile->load('user'); }

    public function store(Request $request)
    {
        return $this->persist($request);
    }

    public function update(Request $request, Profile $profile)
    {
        abort_unless($profile->user_id === $request->user()->id || $request->user()->isAdmin(), 403);
        return $this->persist($request, $profile);
    }

    private function persist(Request $request, ?Profile $profile = null)
    {
        $data = $request->validate([
            'bio' => ['nullable', 'string'], 'experience' => ['nullable', 'string', 'max:120'],
            'skills' => ['nullable', 'array'], 'skills.*' => ['string', 'max:60'],
            'resume_url' => ['nullable', 'url'], 'target_role' => ['nullable', 'string', 'max:120'],
            'pricing' => ['nullable', 'numeric', 'between:5,50'], 'availability' => ['nullable', 'array'],
        ]);
        return Profile::updateOrCreate(['user_id' => $profile?->user_id ?? $request->user()->id], $data);
    }
}
