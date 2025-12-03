<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    public function login(Request $request)
{
    // Validate input
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    // Unique key for rate limiting (based on email + IP)
    $key = Str::lower($request->input('email')).'|'.$request->ip();
    $maxAttempts = 5;       // Maximum allowed attempts
    $decaySeconds = 60;     // Lockout duration in seconds

    // Check if the user has exceeded the max attempts
    if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
        $seconds = RateLimiter::availableIn($key);
        return api_response(
            "Too many login attempts. Please try again in {$seconds} seconds.",
            429,
            [
                'retry_after' => $seconds,
                'message' => "You have been locked out due to too many failed attempts."
            ]
        );
    }

    // Try to fetch the user
    $user = User::where('email', $request->email)->first();

    // If user exists and password is correct
    if ($user && Hash::check($request->password, $user->password)) {
        // Reset the attempts counter after successful login
        RateLimiter::clear($key);

        // Create and return a new auth token
        $token = $user->createToken('auth_token')->plainTextToken;
        return api_response('Login successful', 200, $token);
    }

    // If login failed → increase attempts count
    RateLimiter::hit($key, $decaySeconds);

    // Get attempts info
    $attempts = RateLimiter::attempts($key); // Current failed attempts
    $remaining = $maxAttempts - $attempts;   // Remaining tries before lockout

    // Return response with reminder message
    return api_response(
        'Invalid credentials',
        401,
        [
            'remaining_attempts' => $remaining > 0 ? $remaining : 0,
            'max_attempts' => $maxAttempts,
            'message' => $remaining > 0
                ? "You have {$remaining} attempts left before being locked out."
                : "Account temporarily locked. Try again in {$decaySeconds} seconds."
        ]
    );
}

    public function logout()
    {

        request()->user()->currentAccessToken()->delete();
        return api_response('Logout successful', 200, null);
    }
}
