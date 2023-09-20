<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class AirlineResource extends JsonResource
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
            'legal_name' => $this->legal_name,
            'starting_balance' => $this->starting_balance,
            'contract' => $this->contract ? env('APP_URL', 'http://localhost:8000') . Storage::url('contracts/' . $this->contract) : null,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
