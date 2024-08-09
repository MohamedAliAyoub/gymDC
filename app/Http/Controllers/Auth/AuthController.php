<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\AuthResource;
use App\Mail\SendForgotPasswordCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use function App\Http\Helpers\uploadImage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Authenticate user and generate JWT token",
     *     @OA\RequestBody(
     *         description="User's credentials",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="email",
     *                 description="User's email",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 description="User's password",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Login successful"),
     *     @OA\Response(response="401", description="Invalid credentials"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);


        $credentials = $request->only('email', 'password');

        // Attempt to authenticate the user
        if (!Auth::guard('api')->attempt($credentials)) {
            // If authentication fails, return error response
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Authentication successful, generate JWT token
        $token = Auth::guard('api')->attempt($credentials);

        // Get the authenticated user
        $user = Auth::guard('api')->user();

        // Return success response with token and user data
        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'user' => AuthResource::make($user),
            'token' => $token,

        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="User's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="image",
     *         in="query",
     *         description="User's profile image (optional)",
     *         required=false,
     *         @OA\Schema(type="string", format="binary")
     *     ),
     *     @OA\Response(response="201", description="User registered successfully"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */

    public function register(Request $request): JsonResponse
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,heic|max:8048',
            'mobile' => ['required', 'regex:/^(\+?\d{1,4}|\d{1,4})?\s?\d{7,14}$/']
        ],
            [
                'mobile.required' => 'A phone number is required',
                'mobile.regex' => 'Enter a valid phone number',
            ]);


        // Upload the profile picture and get the path
        if ($request->hasFile('image'))
            $path = uploadImage($request->file('image'), 'public', 'users');
        else
            $path = null;


        // Create and save the new user
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'image' => $path,
            'mobile' => $request->mobile,
            'type' => 8
        ]);
        $user->save();


        // Generate JWT token for the newly registered user
        $token = Auth::guard('api')->login($user);


        // Return a success response
        return response()->json([
            'message' => 'User registered successfully',
            'data' => AuthResource::make($user),
            'token' => $token
        ], 201);
    }


    /**
     * @OA\Post(
     *     path="/forgot-password",
     *     summary="Send a password reset link to the user's email",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Password reset link sent successfully"),
     *     @OA\Response(response=400, description="Invalid email")
     * )
     */

    public function forgotPassword(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['We couldn\'t find an account with that email address.'],
            ]);
        }

        $token =  rand(100000, 999999);
        DB::table("password_resets")->updateOrInsert(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        Mail::to($user->email)->send(new SendForgotPasswordCode($token));


        return response()->json([
            'message' => 'A 6-digit code has been sent to your email address for password reset.'
        ], 200);
    }


    /**
     * @OA\Post(
     *     path="/api/reset-password",
     *     summary="Reset the user's password",
     *     @OA\RequestBody(
     *         description="User's new password data",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="email",
     *                 description="User's email",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 description="User's new password",
     *                 type="string"
     *             ),
     *          @OA\Property(
     *                 property="password_confirmation",
     *                 description="User's password confirmation",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="token",
     *                 description="Password reset token",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Password reset successfully"),
     *     @OA\Response(response="400", description="Invalid token or email")
     * )
     */

    public function resetPassword(Request $request): JsonResponse
    {
        // Step 1: Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Step 2: Find the user by email
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Step 3: Verify the token
        $passwordReset = DB::table('password_resets')->where([
            ['email', $request->email],
            ['token', $request->token],
        ])->first();

        if (!$passwordReset) {
            return response()->json(['error' => 'Invalid token'], 400);
        }

        // Step 4: Update the password
        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Step 5: Delete the token
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Step 6: Trigger Password Reset Event
        event(new PasswordReset($user));

        return response()->json(['message' => 'Password reset successful'], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Log out the authenticated user",
     *     @OA\Response(response="200", description="User logged out successfully")
     * )
     */
    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();

        return response()->json([
            'message' => 'User logged out successfully',
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/change-password",
     *     summary="Change the user's password",
     *     @OA\Parameter(
     *         name="current_password",
     *         in="query",
     *         description="Current password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="new_password",
     *         in="query",
     *         description="New password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="new_password_confirmation",
     *         in="query",
     *         description="Confirm new password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Password changed successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::guard('api')->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Invalid current password',
            ], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Password changed successfully',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/profile",
     *     summary="Get the authenticated user's profile",
     *     @OA\Response(response="200", description="User profile retrieved successfully")
     * )
     */
    public function profile(Request $request): JsonResponse
    {
        $user = Auth::guard('api')->user();

        return response()->json([
            'user' => AuthResource::make($user),
        ]);
    }

}
