<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\CarResource;
use App\Http\Resources\EntranceTicketResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InclusiveEntranceTicketResource extends JsonResource
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
            'product' => new EntranceTicketResource($this->product),
            'variation' => new EntranceTicketVariationResource($this->variation),
            'selling_price' => $this->selling_price,
            'cost_price' => $this->cost_price,
            'quantity' => $this->quantity,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
