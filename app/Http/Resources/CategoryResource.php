<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data =  [
            'id' => $this->id,
            'category_name' => $this->name,
            'slug' => $this->slug,
            'status' => $this->status(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),


        ];
        if (!request()->is('api/show/post/*') && request()->is('api/all/posts')) {
            $data['posts'] = PostResource::collection($this->posts);
        }
        return $data;
    }
}
