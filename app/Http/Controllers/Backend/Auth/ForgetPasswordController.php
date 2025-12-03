<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Http\Request;


class ForgetPasswordController extends Controller
{
    public $otp;
    public function __construct()
    {
        $this->otp = new \Ichtrojan\Otp\Otp();
    }

    public function showLinkRequestForm()
    {
        return view('backend.auth.forgot-password');
    }

    public function sendOtpEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = \App\Models\Admin::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No account found with that email address.']);
        }

        $user->notify(new \App\Notifications\SendOtpNotification());

        return redirect()->route('admin.forgot_password_code', ['email' => $user->email])->with('status', 'code sent successfully!');

    }
    public function showCodeForm($email)
    {
        return view('backend.auth.forgot-password-code', compact('email'));
    }
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $otp = $this->otp->validate($request->email, $request->token);
        if (!$otp->status) {
            return back()->withErrors(['token' => 'The provided token is invalid or expired.']);
        }

        return redirect()->route('admin.reset_password.form',['email' => $request->email]);
    }
    public function showResetForm($email)
    {
        return view('backend.auth.reset-password', compact('email'));
    }
    public function resetPassword(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = \App\Models\Admin::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No account found with that email address.']);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        Flasher::addSuccess('Password reset successfully!');
        return redirect()->route('admin.login');

    }





}
