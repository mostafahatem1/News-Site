<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RelateSiteResource;
use App\Http\Resources\SettingResource;
use App\Models\RelatedSite;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function getSettings()
    {
        $setting = Setting::first();
        $relateSites = $this->relateSite();

        if (!$setting) {
            return api_response('Settings not found', 404, null);
        }

        $data = [
            'setting' => SettingResource::make($setting),
            'related_sites' => $relateSites,
        ];


        return api_response('Settings retrieved successfully', 200, $data);
    }

    public function relateSite(){
        $relateSites = RelatedSite::select(['name', 'url'])->get();
        if ($relateSites->isEmpty()) {
            return api_response('No related sites found', 404, null);
        }
        return  $relateSites;
    }
}
