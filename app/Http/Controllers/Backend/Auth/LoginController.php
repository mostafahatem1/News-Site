<?php

namespace App\Http\Controllers\Backend\Auth;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
   public function loginForm()
   {
      return view('backend.auth.login');
   }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'remember' => 'in:on,off', // Ensure 'remember' is either 'on' or 'off'
        ]);

        $credentials = $request->only('email', 'password');

        if (auth()->guard('admin')->attempt($credentials, $request->filled('remember'))) {
            return redirect()->intended(RouteServiceProvider::ADMIN);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    public function logout(Request $request)
    {
        auth()->guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

   public function registerForm()
   {
      return view('backend.auth.register');
   }


}
