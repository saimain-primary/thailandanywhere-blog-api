<?php

namespace App\Http\Resources;

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
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'content' => $this->content,
            'featured_image' => $this->featured_image,
            'category' => new CategoryResource($this->category),
            'tags' => json_decode($this->tags, true),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
