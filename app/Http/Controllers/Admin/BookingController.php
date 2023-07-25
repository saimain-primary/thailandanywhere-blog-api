<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Traits\ImageManager;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
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

        $query = Booking::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $data = $query->paginate($limit);
        return $this->success(BookingResource::collection($data)
            ->additional([
                'meta' => [
                    'total_page' => (int) ceil($data->total() / $data->perPage()),
                ],
            ])
            ->response()
            ->getData(), 'Booking List');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'customer_id' => 'required',
            'sold_from' => 'required|string',
            'payment_method' => 'required|string',
            'payment_status' => 'required|string',
            'booking_date' => 'required|string',
            'items' => 'required'
        ]);


        $data = [
            'customer_id' => $request->customer_id,
            'sold_from' => $request->sold_from,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'booking_date' => $request->booking_date,
            'money_exchange_rate' => $request->money_exchange_rate,
            'discount' => $request->discount,
            'comment' => $request->comment,
        ];

        $save = Booking::create($data);

        foreach ($request->items as $key => $item) {
            $data = [
                'booking_id' => $save->id,
                'product_type' => $item['product_type'],
                'product_id' => $item['product_id'],
                'service_date' => $item['service_date'],
                'quantity' => $item['quantity'],
                'duration' => $item['duration'],
                'selling_price' => $item['selling_price'],
                'cost_price' => $item['cost_price'],
                'payment_method' => $item['payment_method'],
                'payment_status' => $item['payment_status'],
                'exchange_rate' => $item['exchange_rate'],
                'comment' => $item['comment'],
                'reservation_status' => $item['reservation_status'],
            ];

            if (isset($request->items[$key]['receipt_image'])) {
                $receiptImage = $request->items[$key]['receipt_image'];
                if ($receiptImage) {
                    $fileData = $this->uploads($receiptImage, 'images/');
                    $data['receipt_image'] = $fileData['fileName'];
                }
            }

            if (isset($request->items[$key]['confirmation_letter'])) {
                $file = $request->items[$key]['confirmation_letter'];
                if ($file) {
                    $fileData = $this->uploads($file, 'files/');
                    $data['confirmation_letter'] = $fileData['fileName'];
                }
            }


            BookingItem::create($data);
        }

        return $this->success(new BookingResource($save), 'Successfully created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $find = Booking::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        return $this->success(new BookingResource($find), 'Booking Detail');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $find = Booking::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }


        $data = [
            'customer_id' => $request->customer_id ?? $find->customer_id,
            'sold_from' => $request->sold_from ?? $find->sold_from,
            'payment_method' => $request->payment_method ?? $find->payment_method,
            'payment_status' => $request->payment_status ?? $find->payment_status,
            'booking_date' => $request->booking_date ?? $find->booking_date,
            'money_exchange_rate' => $request->money_exchange_rate ?? $find->money_exchange_rate,
            'discount' => $request->discount ?? $find->discount,
            'comment' => $request->comment ?? $find->comment,
        ];

        $find->update($data);

        if ($request->items) {

            foreach ($find->items as $key => $item) {
                if ($item->receipt_image) {
                    Storage::delete('public/images/' . $item->receipt_image);
                }

                BookingItem::where('id', $item->id)->delete();
            }

            foreach ($request->items as $key => $item) {
                $data = [
                    'booking_id' => $find->id,
                    'product_type' => $item['product_type'],
                    'product_id' => $item['product_id'],
                    'service_date' => $item['service_date'],
                    'quantity' => $item['quantity'],
                    'duration' => $item['duration'],
                    'selling_price' => $item['selling_price'],
                    'comment' => $item['comment'],
                    'reservation_status' => $item['reservation_status'],
                ];

                if (isset($request->items[$key]['receipt_image'])) {
                    $receiptImage = $request->items[$key]['receipt_image'];
                    if ($receiptImage) {
                        $fileData = $this->uploads($receiptImage, 'images/');
                        $data['receipt_image'] = $fileData['fileName'];
                    }
                }

                BookingItem::create($data);
            }
        }

        return $this->success(new BookingResource($find), 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $find = Booking::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        foreach ($find->items as $key => $item) {
            if ($item->receipt_image) {
                Storage::delete('public/images/' . $item->receipt_image);
            }
            BookingItem::where('id', $item->id)->delete();
        }


        $find->delete();
        return $this->success(null, 'Successfully deleted');
    }
}
