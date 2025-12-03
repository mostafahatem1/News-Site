<?php

namespace App\Http\Resources;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (request()->is('api/show/post/*')) {
            return [

                'title' => $this->title,
                'num_of_views' => $this->num_of_views,
                'slug' => $this->slug,
                'desc' => $this->desc,
                'status' => $this->status(),
                'comment_able' => $this->comment_able(),
                'created_at' => $this->created_at->format('Y-m-d H:i:s'),
                'post_url' => route('frontend.post.show', $this->slug),
                'user' => UserResource::make($this->user),
                'category' => CategoryResource::make($this->category),
            ];
        } else  {
            return [
                'id' => $this->id,
                'title' => $this->title,
                 'desc' => $this->desc,
                'num_of_views' => $this->num_of_views,
                'slug' => $this->slug,
                'status' => $this->status(),
                'created_at' => $this->created_at->format('Y-m-d H:i:s'),
                'user' => UserResource::make($this->user),
                'media' => ImageResource::collection($this->images),
                'category' => $this->category->name,

            ];
        }
    }
}
