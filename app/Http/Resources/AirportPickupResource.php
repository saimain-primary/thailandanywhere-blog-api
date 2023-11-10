<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PrivateVanTourCarResource;
use App\Http\Resources\PrivateVanTourTagResource;
use App\Http\Resources\PrivateVanTourCityResource;
use App\Http\Resources\PrivateVanTourImageResource;
use App\Http\Resources\PrivateVanTourDestinationResource;

class AirportPickupResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'cover_image' => $this->cover_image ? config('app.url') . Storage::url('images/' . $this->cover_image) : null,
            'cars' => PrivateVanTourCarResource::collection($this->cars),
            'destinations' => PrivateVanTourDestinationResource::collection($this->destinations),
            'tags' => PrivateVanTourTagResource::collection($this->tags),
            'cities' => PrivateVanTourCityResource::collection($this->cities),
            'images' => $this->images ? PrivateVanTourImageResource::collection($this->images) : null,
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
        ];
    }
}
