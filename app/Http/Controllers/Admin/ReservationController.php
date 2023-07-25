<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Traits\ImageManager;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\BookingItemResource;

class ReservationController extends Controller
{
    use ImageManager;
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $search = $request->query('search');

        $query = BookingItem::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $data = $query->paginate($limit);
        return $this->success(BookingItemResource::collection($data)
            ->additional([
                'meta' => [
                    'total_page' => (int) ceil($data->total() / $data->perPage()),
                ],
            ])
            ->response()
            ->getData(), 'Booking Item List');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $find = BookingItem::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        return $this->success(new BookingItemResource($find), 'Booking Item Detail');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $find = BookingItem::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        $data = [
            'service_date' => $request->service_date ?? $find->service_date,
            'quantity' => $request->quantity ?? $find->quantity,
            'selling_price' => $request->selling_price ?? $find->selling_price,
            'duration' => $request->duration ?? $find->duration,
            'cost_price' => $request->cost_price ?? $find->cost_price,
            'payment_method' => $request->payment_method ?? $find->payment_method,
            'payment_status' => $request->payment_status ?? $find->payment_status,
            'exchange_rate' => $request->exchange_rate ?? $find->exchange_rate,
            'reservation_status' => $request->reservation_status ?? $find->reservation_status,
            'comment' => $request->comment ?? $find->comment,
        ];


        if ($file = $request->file('receipt_image')) {
            if ($find->receipt_image) {
                Storage::delete('public/images/' . $find->receipt_image);
            }

            $fileData = $this->uploads($file, 'images/');
            $data['receipt_image'] = $fileData['fileName'];
        }

        if ($file = $request->file('confirmation_letter')) {
            if ($find->confirmation_letter) {
                Storage::delete('public/images/' . $find->confirmation_letter);
            }

            $fileData = $this->uploads($file, 'files/');
            $data['confirmation_letter'] = $fileData['fileName'];
        }


        $find->update($data);


        // check all reservation status and update booking reservation status

        $booking = Booking::find($find->booking_id);

        // Check if all item's status is 'confirm'
        $allConfirmed = $booking->items->every(function ($item) {
            return $item->reservation_status === 'reserved';
        });

        if ($allConfirmed) {
            $booking->update(['reservation_status' => 'confirmed']);
        }

        return $this->success(new BookingItemResource($find), 'Successfully updated');
    }
}
