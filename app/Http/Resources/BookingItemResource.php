<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $product = null;

        // return [
        //     'id' => $this->id,
        //     'product_type' => $this->product_type,
        //     'product' => $product,
        //     'id' => $this->id,
        //     'id' => $this->id,
        //     'id' => $this->id,
        //     'id' => $this->id,
        //     'id' => $this->id,
        //     'id' => $this->id,
        // ];
    }
}
