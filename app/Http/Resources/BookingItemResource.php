<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\AirportPickup;
use Illuminate\Support\Facades\Storage;
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
        switch ($this->product_type) {
            case 'App\Models\PrivateVanTour':
                $product = new PrivateVanTourResource($this->product);
                break;
            case 'App\Models\GroupTour':
                $product = new GroupTourResource($this->product);
                break;
            case 'App\Models\EntranceTicket':
                $product = new EntranceTicketResource($this->product);
                break;
            case 'App\Models\AirportPickup':
                $product = new AirportPickupResource($this->product);
                break;
            default:
                $product = null;
                break;
        }
        return [
            'id' => $this->id,
            'product_type' => $this->product_type,
            'product' => $product,
            'service_date' => $this->service_date,
            'quantity' => $this->quantity,
            'duration' => $this->duration,
            'selling_price' => $this->selling_price,
            'cost_price' => $this->cost_price,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'exchange_rate' => $this->exchange_rate,
            'confirmation_letter' => $this->confirmation_letter ? env('APP_URL', 'http://localhost:8000') . Storage::url('files/' . $this->confirmation_letter) : null,
            'selling_price' => $this->selling_price,
            'comment' => $this->comment,
            'reservation_status' => $this->reservation_status,
            'receipt_image' => $this->receipt_image ? env('APP_URL', 'http://localhost:8000') . Storage::url('images/' . $this->receipt_image) : null,
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
        ];
    }
}
