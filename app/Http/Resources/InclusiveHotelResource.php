<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InclusiveHotelResource extends JsonResource
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
            'product' => new HotelResource($this->product),
            'room' => new RoomResource($this->room),
            'selling_price' => $this->selling_price,
            'cost_price' => $this->cost_price,
            'quantity' => $this->quantity,
            'checkin_date' => $this->checkin_date,
            'checkout_date' => $this->checkout_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
