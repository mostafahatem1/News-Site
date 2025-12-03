<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data =  [
            'name' => $this->name,
            'status' => $this->status(),
            'created_at' => $this->created_at->format('d/m/Y'),
        ];

        if($request->is('api/user/account')) {
            $data['email'] = $this->email;
            $data['username'] = $this->username;
            $data['phone'] = $this->phone;
            $data['country'] = $this->country;
            $data['city'] = $this->city;
            $data['street'] = $this->street;
            $data['gender'] = $this->gender;
            $data['image'] = asset('frontend/img/user/'. $this->image);
        }

        return $data;
    }
}
