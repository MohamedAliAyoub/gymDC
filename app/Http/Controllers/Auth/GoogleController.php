<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Laravel\Socialite\Facades\Socialite;


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
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the Google callback after authentication.
     *
     * @return RedirectResponse
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
            $user = Socialite::driver('google')->user();
            $finduser = User::where('google_id', $user->id)->first();
            if ($finduser) {
                Auth::login($finduser);
                return redirect()->intended('/dashboard');
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id
                ]);
                Auth::login($newUser);
                return redirect()->intended('/home');
            }
        }
        catch
            (Exception $e){
                dd($e->getMessage());

            }
    }
}
