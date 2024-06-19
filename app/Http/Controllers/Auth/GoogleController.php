<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\JsonResponse;


class GoogleController extends Controller
{

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @OA\Get(
     *     path="/auth/google",
     *     summary="Redirect to Google authentication",
     *     tags={"Authentication"},
     *     @OA\Response(response=302, description="Redirect to Google authentication page")
     * )
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

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
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid Google credentials.'], 401);
        }

        $existingUser = User::where('email', $user->getEmail())->first();

        if ($existingUser) {
            $token = JWTAuth::fromUser($existingUser);
        } else {
            $newUser = new User;
            $newUser->name = $user->getName();
            $newUser->email = $user->getEmail();
            $newUser->google_id = $user->getId();
            $newUser->password = bcrypt($user->getId());
            $newUser->save();

            $token = JWTAuth::fromUser($newUser);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'user' => $existingUser ?? $newUser,
            'token' => $token,
        ]);
    }
}
