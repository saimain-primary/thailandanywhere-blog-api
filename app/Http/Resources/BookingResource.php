<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $balanceDue = $this->items ?  $this->items->sum(function ($item) {
            return $item->selling_price * $item->quantity;
        }) : 0;

        return [
            'id' => $this->id,
            'crm_id' => $this->crm_id,
            'customer' => $this->customer,
            'sold_from' => $this->sold_from,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'booking_date' => $this->booking_date,
            'money_exchange_rate' => $this->money_exchange_rate,
            'discount' => $this->discount,
            'comment' => $this->comment,
            'reservation_status' => $this->reservation_status,
            'balance_due' => $balanceDue,
            'created_by' => $this->createdBy,
            'bill_to' => $this->createdBy ? $this->createdBy->name : "-",
            'items' => BookingItemResource::collection($this->items),
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
        ];
    }
}
