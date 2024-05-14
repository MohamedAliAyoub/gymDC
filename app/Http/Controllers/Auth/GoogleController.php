<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\JsonResponse;


class GoogleController extends Controller
{


    /**
     * Handle the Google callback after authentication.
     *
     * @return JsonResponse
     *
     * @OA\Get(
     *     path="/auth/google/callback",
     *     summary="Handle Google callback after authentication",
     *     tags={"Authentication"},
     *     @OA\Response(response=302, description="Redirect after successful Google authentication")
     * )
     */
    public function handleGoogleCallback(): JsonResponse
    {
    try {
        $user = Socialite::driver('google')->user();
        $finduser = User::where('google_id', $user->id)->first();
        if ($finduser) {
            $token = $finduser->createToken('authToken')->plainTextToken;
            return response()->json(['user' => $finduser, 'token' => $token]);
        } else {
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->id
            ]);
            $token = $newUser->createToken('authToken')->plainTextToken;
            return response()->json(['user' => $newUser, 'token' => $token]);
        }
    }
    catch (Exception $e){
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}
