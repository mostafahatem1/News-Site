<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\SendOtpNotification;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public $otp;

     public function __construct(Otp $otp)
    {
        $this->otp = $otp;
    }

    function verifyEmail(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:5',
        ]);
        $user = request()->user();

        $otp2 = $this->otp->validate($user->email, $request->code);
        if ($otp2->status == false) {
            return api_response('Otp not valid', 400);
        }

        $user->update(['email_verified_at' => now()]);
        return api_response('Email verified', 200);

    }




    function RestCodeOtp()
    {
      $user = request()->user();
         $user->notify(new SendOtpNotification());
        return api_response('Otp send to email', 200);

    }
}
