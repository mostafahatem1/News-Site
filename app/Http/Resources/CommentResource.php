<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'comments' => $this->comment,
            'user_name' => $this->user->name,
            'user_image' => asset('frontend/img/user/' . $this->user->image),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
