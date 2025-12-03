<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SendOtpNotification;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public $otp;
    public function __construct(Otp $otp)
    {
        $this->otp = $otp;
    }

    public function register(Request $request){

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'street' => ['nullable', 'string', 'max:100'],
            'gender' => ['required', 'in:0,1'], // 0 = Male, 1 = Female
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        DB::beginTransaction();
        try{
        $image = $request->file('image');
        $imageName = null;
        if (isset($image) && $image->isValid()) {
            $imageName = time() . '_' . Str::slug($request->post('username')) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('frontend/img/user');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);
        }
         $user = User::create([
            'name' => $request->post('name'),
            'username' => $request->post('username'),
            'email' => $request->post('email'),
            'phone' => $request->post('phone'),
            'country' => $request->post('country'),
            'city' => $request->post('city'),
            'street' => $request->post('street'),
            'gender' => $request->post('gender'),
            'image' => $imageName ? $imageName :'default.jpg',
            'password' => Hash::make($request->post('password')),
        ]);
        $user->notify(new SendOtpNotification($user));
        $token = $user->createToken('auth_token')->plainTextToken;
        DB::commit();
        return api_response('Registration successful and OTP sent successfully', 201, $token );
        }catch(\Throwable $e){
            DB::rollBack();
            return api_response('Registration failed: ' . $e->getMessage(), 500, null);
        }


    }
}
