<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InclusiveAirlineTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//         return parent::toArray($request);

        return [
            'id' => $this->id,
            'product' => new AirlineResource($this->product),
            'ticket' => new AirlineTicketResource($this->ticket),
            'selling_price' => $this->selling_price,
            'cost_price' => $this->cost_price,
            'quantity' => $this->quantity,
            'day' => $this->day,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
