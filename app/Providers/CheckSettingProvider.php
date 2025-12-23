<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class CheckSettingProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $setting = null;

        if (Schema::hasTable('settings')) {
            $setting = Setting::firstOr(function () {
                return Setting::create([
                    'site_name' => 'News-EveryDay',
                    'logo' => 'logo.png',
                    'favicon' => 'favicon.ico',
                    'email' => 'news@gmail.com',
                    'facebook' => 'https://www.facebook.com/',
                    'twitter' => 'https://www.twitter.com/',
                    'insagram' => 'https://www.instagram.com/',
                    'youtupe' => 'https://www.youtupe.com/',
                    'country' => 'Egypt',
                    'city' => 'Alex',
                    'street' => 'Elsharawy',
                    'phone' => '01222333434',
                    'small_desc' => '23 of PARAGE is equality of condition, blood, or dignity; specifically : equality between persons (as brothers) one of whom holds a part of a fee ',
                ]);
            });
        }

        view()->share('setting', $setting);
    }
}