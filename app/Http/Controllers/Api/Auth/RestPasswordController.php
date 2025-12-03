<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;

class RestPasswordController extends Controller
{
    public $otp;
    public function __construct(Otp $otp) {
        $this->otp = $otp;
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric|digits:5',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return api_response('User not found', 404);
        }

         $otp2 = $this->otp->validate($user->email, $request->otp);
        if ($otp2->status == false) {
            return api_response('Otp not valid', 400);
        }
        $user->update(['password' => bcrypt($request->password)]);
        return api_response('Password reset successfully', 200);
    } 
}
