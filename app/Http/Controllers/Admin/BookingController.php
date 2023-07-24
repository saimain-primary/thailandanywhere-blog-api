<?php

namespace App\Http\Controllers\Admin;

use App\Traits\ImageManager;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\BookingItem;

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
            'crm_id'  => 'required|string|max:225|unique:bookings,crm_id',
            'customer_id' => 'required',
            'sold_from' => 'required|string',
            'payment_method' => 'required|string',
            'payment_status' => 'required|string',
            'booking_date' => 'required|string',
            'items' => 'required'
        ]);


        $data = [
            'crm_id' => $request->crm_id,
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
                'comment' => $item['comment'],
                'reservation_status' => $item['reservation_status'],
            ];

            $receiptImage = $request->items[$key]['receipt_image'];
            if ($receiptImage) {
                $fileData = $this->uploads($receiptImage, 'images/');
                $data['receipt_image'] = $fileData['fileName'];
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
        $find = Car::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        return $this->success(new CarResource($find), 'Car Detail');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $find = Car::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        $data = [
            'name' => $request->name ?? $find->name,
            'max_person' => $request->max_person ?? $find->max_person,
        ];


        $find->update($data);

        return $this->success(new CarResource($find), 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $find = Car::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        $find->delete();
        return $this->success(null, 'Successfully deleted');
    }
}
