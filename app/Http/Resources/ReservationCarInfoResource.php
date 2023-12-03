<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationCarInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request)
        return [
            'id' => $this->id,
            'booking_item_id' => $this->booking_item_id,
            'ref_number' => $this->ref_number,
            'account_holder_name' => $this->account_holder_name,
            'supplier_name' => $this->supplier_name,
            'driver_name' => $this->driver_name,
            'driver_contact' => $this->driver_contact,
            'car_number' => $this->car_number,
            'car_photo' => $this->car_photo ? config('app.url') . Storage::url('images/' . $this->car_photo) : null,
            'deleted_at' =>  $this->deleted_at,
            'updated_at' =>  $this->updated_at,
            'created_at' =>  $this->created_at,
        ];
    }
}
