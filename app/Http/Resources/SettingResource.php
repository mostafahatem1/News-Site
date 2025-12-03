<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'site_name'   => $this->site_name,
            'logo'        => asset($this->logo),
            'favicon'     => asset($this->favicon),
            'email'       => $this->email,
            'facebook'    => $this->facebook,
            'twitter'     => $this->twitter,
            'insagram'    => $this->insagram,
            'youtupe'     => $this->youtupe,
            'phone'       => $this->phone,
            'small_desc'  => $this->small_desc,
            'address'    => [
                'country' => $this->country,
                'city'    => $this->city,
                'street'  => $this->street,
            ],
        ];
    }
}
