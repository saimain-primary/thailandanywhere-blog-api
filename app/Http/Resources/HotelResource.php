<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
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
            'name' => $this->name,
            'city' => new CityResource($this->city),
            'place' => $this->place,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
