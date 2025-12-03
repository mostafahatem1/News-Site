<?php

namespace App\Http\Controllers\Frontend\Dashboard;


use App\Http\Controllers\Controller;
use App\Http\Requests\backend\UserRequest;
use App\Models\User;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class SettingController extends Controller
{
    use \App\Traits\UploadImages;

    public function index()
    {
        return view('frontend.dashboard.setting');
    }
    public function updateSetting(UserRequest $request)
    {


        $user = User::findOrFail(auth()->id());

        try{
            DB::beginTransaction();

            // Current password Check
            if ($request->filled('password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
                }
            }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

            // Handle image upload if an image is provided
            $imageName = $this->uploadImage($request->file('image'), $request->username, 'frontend/img/user', $user->image);
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            // Update user details
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->country = $request->country;
            $user->city = $request->city;
            $user->street = $request->street;
            $user->gender = $request->gender;
            $user->image = $imageName ? $imageName : $user->image;

            // Check if password is filled
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }
            $user->save();
            DB::commit();
            Flasher::addSuccess('Update successful!');
            return redirect()->back();


        } catch (\Exception $e) {

            DB::rollBack();
            Flasher::addError('Update failed!');
            return redirect()->back();

        }
    }

}
