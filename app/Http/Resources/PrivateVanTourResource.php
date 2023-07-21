<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class PrivateVanTourResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'sku_code' => $this->sku_code,
            'name' => $this->name,
            'description' => $this->description,
            'long_description' => $this->long_description,
            'cover_image' => $this->cover_image ? env('APP_URL', 'http://localhost:8000') . Storage::url('images/' . $this->cover_image) : null,
            'cars' => PrivateVanTourCarResource::collection($this->cars),
            'destinations' => PrivateVanTourDestinationResource::collection($this->destinations),
            'tags' => PrivateVanTourTagResource::collection($this->tags),
            'cities' => PrivateVanTourCityResource::collection($this->cities),
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
        ];
    }
}
