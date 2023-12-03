<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InclusiveProductResource extends JsonResource
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
            'inclusive_id' => $this->inclusive_id,
            'product_type' => $this->product_type,
            'product' => $this->product,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
