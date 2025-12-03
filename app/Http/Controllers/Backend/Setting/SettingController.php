<?php

namespace App\Http\Controllers\Backend\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:settings')->only(['settings']);
        $this->middleware('can:settings_update')->only(['update']);
    }

    public function settings()
    {
      $setting = Setting::first();


        return view('backend.settings.show', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:2048',
            'email' => 'required|email',
            'facebook' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'insagram' => 'nullable|string|max:255',
            'youtupe' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'small_desc' => 'nullable|string|max:1000',
        ]);

        $setting = Setting::first();

        $setting->site_name = $request->site_name;
        $setting->email = $request->email;
        $setting->facebook = $request->facebook;
        $setting->twitter = $request->twitter;
        $setting->insagram = $request->insagram;
        $setting->youtupe = $request->youtupe;
        $setting->country = $request->country;
        $setting->city = $request->city;
        $setting->street = $request->street;
        $setting->phone = $request->phone;
        $setting->small_desc = $request->small_desc;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('frontend/img'), $logoName);
            $setting->logo = $logoName;
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $favicon = $request->file('favicon');
            $faviconName = 'favicon_' . time() . '.' . $favicon->getClientOriginalExtension();
            $favicon->move(public_path(), $faviconName);
            $setting->favicon = $faviconName;
        }

        $setting->save();
        Flasher::addSuccess('Settings updated successfully.');
        return redirect()->route('admin.settings');
    }
}
