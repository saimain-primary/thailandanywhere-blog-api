<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        $imagesArr = [];

        $postImage = json_decode($request->images);
        foreach ($postImage as $image) {
            array_push($imagesArr, env('APP_URL') . Storage::url('images/' . $image));
        }

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'content' => $this->content,
            'featured_image' => env('APP_URL') . Storage::url('images/' . $this->featured_image),
            'images' => $imagesArr,
            'category' => new CategoryResource($this->category),
            'tags' => json_decode($this->tags, true),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
