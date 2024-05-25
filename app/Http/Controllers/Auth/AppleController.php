<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Firebase\JWT\JWT;
use Laravel\Socialite\Facades\Socialite;

class AppleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('apple')
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        $socialiteUser = Socialite::driver('apple')->stateless()->user();

        $user = User::firstOrCreate([
            'email' => $socialiteUser->email,
        ], [
            'name' => $socialiteUser->name,
            'apple_id' => $socialiteUser->id,
        ]);

        $payload = [
            'iss' => 'your-issuer',
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 60*60, // 1 hour expiration
        ];

        $jwt = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        return response()->json(['token' => $jwt]);
    }
}
