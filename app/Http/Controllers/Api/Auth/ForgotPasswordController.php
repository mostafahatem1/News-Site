<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\SendOtpNotification;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);
        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user) {
            return api_response('User not found', 404);
        }

        $user->notify(new SendOtpNotification());
        return api_response('Otp send to email', 200);
    }
    
}
