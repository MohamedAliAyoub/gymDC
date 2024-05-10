<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        // Validate the incoming request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($request->only('email', 'password'))) {
            // Authentication passed, return a success response
            return response()->json(['message' => 'Successfully logged in', 'user' => auth()->user()], 200);
        }

        // Authentication failed, return an error response
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    /**
     * Register a new user.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Create and save the new user
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->save();

        // Return a success response
        return response()->json(['message' => 'User registered successfully'], 201);
    }


    /**
     * Send a reset link to the given user.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        // Validate the incoming request data
        $request->validate(['email' => 'required|email']);

        // Send the password reset link
        $status = Password::sendResetLink($request->only('email'));

        // Check the response and return a success or error message
        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['error' => __($status)], 400);
    }
    /**
     * Reset the user's password.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        // Validate the incoming request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required|string',
        ]);

        // Reset the user's password
        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->save();

            event(new PasswordReset($user));
        });

        // Check the response and return a success or error message
        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['error' => __($status)], 400);
    }

}
