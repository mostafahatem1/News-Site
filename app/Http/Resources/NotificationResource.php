<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'post_title' => $this->data['post_title'],
            'comment' => $this->data['comment'],
            'commented_by' => $this->data['commented_by'],
             'user_id' => $this->data['user_id'],
            'commented_at' => $this->data['commented_at'].date('Y-m-d H:i:s'),
            // get link as slug only
           'post_slug' => isset($this->data['link']) ? ltrim(pathinfo(parse_url($this->data['link'], PHP_URL_PATH), PATHINFO_BASENAME), '/') : null,




        ];
    }
}
