<?php

namespace App\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\backend\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
 use \App\Traits\UploadImages;

    public function updateUser(UserRequest $request, $id)
    {


        $user = User::find($id);

        if (!$user) {
            return api_response('User not found', 404);
        }

        try{
            DB::beginTransaction();

            // Current password Check
            if ($request->filled('password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json(['error' => 'Current password is incorrect.'], 422);
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

            return api_response('User updated successfully', 200, new UserResource($user));


        } catch (\Exception $e) {

            DB::rollBack();

            return api_response('Update failed!', 500);

        }
    }
}
