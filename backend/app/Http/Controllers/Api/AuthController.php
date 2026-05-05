<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:interviewee,interviewer'],
        ]);
        $user = User::create($data + ['status' => $data['role'] === 'interviewer' ? 'pending' : 'active']);
        $user->wallet()->create(['balance' => 0, 'transactions' => []]);
        return response()->json(['user' => $user, 'token' => $user->createToken('connecto')->plainTextToken], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);
        abort_unless(Auth::attempt($credentials), 422, 'Invalid credentials.');
        $user = $request->user();
        abort_if($user->status === 'suspended', 403, 'Account suspended.');
        return ['user' => $user->load('profile'), 'token' => $user->createToken('connecto')->plainTextToken];
    }

    public function redirect(string $provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function callback(Request $request, string $provider)
    {
        $social = Socialite::driver($provider)->stateless()->user();
        $role = $request->query('role', 'interviewee');
        $user = User::firstOrCreate(
            ['email' => $social->getEmail()],
            ['name' => $social->getName() ?: $social->getNickname(), 'auth_provider' => $provider, 'role' => $role, 'status' => $role === 'interviewer' ? 'pending' : 'active']
        );
        $user->wallet()->firstOrCreate(['user_id' => $user->id], ['balance' => 0, 'transactions' => []]);
        return redirect(config('app.frontend_url', env('FRONTEND_URL')).'/oauth/success?token='.$user->createToken('connecto')->plainTextToken);
    }

    public function me(Request $request) { return $request->user()->load('profile', 'wallet'); }
    public function logout(Request $request) { $request->user()->currentAccessToken()?->delete(); return response()->noContent(); }
}
