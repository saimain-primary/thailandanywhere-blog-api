<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PrivateVanTourTagResource;
use App\Http\Resources\PrivateVanTourCityResource;
use App\Http\Resources\PrivateVanTourImageResource;

class EntranceTicketResource extends JsonResource
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
            'provider' => $this->provider,
            'place' => $this->place,
            'bank_account_number' => $this->bank_account_number,
            'legal_name' => $this->legal_name,
            'bank_name' => $this->bank_name,
            'payment_method' => $this->payment_method,
            'account_name' => $this->account_name,
            'name' => $this->name,
            'description' => $this->description,
            'cover_image' => $this->cover_image ? env('APP_URL', 'http://localhost:8000') . Storage::url('images/' . $this->cover_image) : null,
            'tags' => PrivateVanTourTagResource::collection($this->tags),
            'cities' => PrivateVanTourCityResource::collection($this->cities),
            'categories' => ProductCategoryResource::collection($this->categories),
            'variations' => $this->variations,
            'images' => $this->images ? PrivateVanTourImageResource::collection($this->images) : null,
            'contacts' => HotelContractResource::collection($this->contracts),
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
        ];
    }
}
