<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Traits\ImageManager;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BookingResource;
use App\Http\Resources\InclusiveResource;
use App\Models\BookingReceipt;
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
        $crmId = $request->query('crm_id');


        $query = Booking::query();

        if (!Auth::user()->is_super) {
            $query->where('created_by', Auth::id())->orWhere('past_user_id', Auth::id());
        }

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        if ($crmId) {
            $query->where('crm_id', $crmId);
        }

        $query->orderBy('created_at', 'desc');
        $data = $query->paginate($limit);
        return $this->success(BookingResource::collection($data)
            ->additional([
                'meta' => [
                    'total_page' => (int)ceil($data->total() / $data->perPage()),
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
            'bank_name' => 'required|string',
            'payment_status' => 'required|string',
            'booking_date' => 'required|string',
            'items' => 'required',
            'sub_total' => 'required',
            'grand_total' => 'required',
            'balance_due' => 'required',
            'balance_due_date' => 'required'
        ]);


        $data = [
            // 'crm_id' => $request->crm_id,
            'customer_id' => $request->customer_id,
            'sold_from' => $request->sold_from,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'payment_currency' => $request->payment_currency,
            'booking_date' => $request->booking_date,
            'bank_name' => $request->bank_name,
            'money_exchange_rate' => $request->money_exchange_rate,
            'sub_total' => $request->sub_total,
            'grand_total' => $request->grand_total,
            'deposit' => $request->deposit,
            'balance_due' => $request->balance_due,
            'balance_due_date' => $request->balance_due_date,
            'discount' => $request->discount,
            'comment' => $request->comment,
            'is_past_info' => $request->is_past_info,
            'past_user_id' => $request->past_user_id,
            'past_crm_id' => $request->past_crm_id,
            'created_by' => Auth::id(),
            'reservation_status' => "awaiting"
        ];

        $save = Booking::create($data);

        if ($file = $request->file('receipt_image')) {
            $fileData = $this->uploads($file, 'images/');
            BookingReceipt::create(['booking_id' => $save->id, 'image' => $fileData['fileName']]);
        }

        foreach ($request->items as $key => $item) {
            $data = [
                'booking_id' => $save->id,
                'crm_id' => $save->crm_id . '_' . str_pad($key + 1, 3, '0', STR_PAD_LEFT),
                'product_type' => $item['product_type'],
                'room_number' => $item['room_number'] ?? null,
                'product_id' => $item['product_id'],
                'car_id' => isset($item['car_id']) ? $item['car_id'] : null,
                'room_id' => isset($item['room_id']) ? $item['room_id'] : null,
                'variation_id' => isset($item['variation_id']) ? $item['variation_id'] : null,
                'service_date' => $item['service_date'] ?? null,
                'quantity' => $item['quantity'] ?? null,
                'duration' => $item['duration'] ?? null,
                'selling_price' => $item['selling_price'] ?? null,
                'cost_price' => $item['cost_price'] ?? null,
                'payment_method' => $item['payment_method'] ?? null,
                'payment_status' => $item['payment_status'] ?? 'not_paid',
                'exchange_rate' => $item['exchange_rate'] ?? null,
                'comment' => $item['comment'] ?? null,
                'special_request' => isset($item['special_request']) ? $item['special_request'] : null,
                'route_plan' => isset($item['route_plan']) ? $item['route_plan'] : null,
                'pickup_location' => isset($item['pickup_location']) ? $item['pickup_location'] : null,
                'pickup_time' => isset($item['pickup_time']) ? $item['pickup_time'] : null,
                'dropoff_location' => isset($item['dropoff_location']) ? $item['dropoff_location'] : null,
                'checkin_date' => isset($item['checkin_date']) ? $item['checkin_date'] : null,
                'checkout_date' => isset($item['checkout_date']) ? $item['checkout_date'] : null,
                'reservation_status' => $item['reservation_status'] ?? "awaiting",
            ];

            if (isset($request->items[$key]['customer_attachment'])) {
                $attachment = $request->items[$key]['customer_attachment'];
                $fileData = $this->uploads($attachment, 'attachments/');
                $data['customer_attachment'] = $fileData['fileName'];
            }

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
            'is_past_info' => $request->is_past_info ?? $find->is_past_info,
            'past_user_id' => $request->past_user_id ?? $find->past_user_id,
            'past_crm_id' => $request->past_crm_id ?? $find->past_crm_id,
            'sold_from' => $request->sold_from ?? $find->sold_from,
            'payment_method' => $request->payment_method ?? $find->payment_method,
            'payment_status' => $request->payment_status ?? $find->payment_status,
            'payment_currency' => $request->payment_currency ?? $find->payment_currency,
            'booking_date' => $request->booking_date ?? $find->booking_date,
            'bank_name' => $request->bank_name ?? $find->bank_name,
            'money_exchange_rate' => $request->money_exchange_rate ?? $find->money_exchange_rate,
            'comment' => $request->comment ?? $find->comment,
            'sub_total' => $request->sub_total ?? $find->sub_total,
            'grand_total' => $request->grand_total ?? $find->grand_total,
            'deposit' => $request->deposit ?? $find->deposit,
            'balance_due' => $request->balance_due ?? $find->balance_due,
            'balance_due_date' => $request->balance_due_date ?? $find->balance_due_date,
            'discount' => $request->discount ?? $find->discount,
            "reservation_status" => 'awaiting',
        ];

        $find->update($data);


        if ($file = $request->file('receipt_image')) {
            $fileData = $this->uploads($file, 'images/');
            BookingReceipt::create(['booking_id' => $find->id, 'image' => $fileData['fileName']]);
        }

        if ($request->items) {

            foreach ($find->items as $key => $item) {
                if ($item->receipt_image) {
                    Storage::delete('public/images/' . $item->receipt_image);
                }
                if ($item->confirmation_letter) {
                    Storage::delete('public/files/' . $item->confirmation_letter);
                }

                BookingItem::where('id', $item->id)->delete();
            }

            foreach ($request->items as $key => $item) {
                $data = [
                    'booking_id' => $find->id,
                    'crm_id' => $find->crm_id . '_' . str_pad($key + 1, 3, '0', STR_PAD_LEFT),
                    'product_type' => $item['product_type'],
                    'room_number' => $item['room_number'] ?? null,
                    'product_id' => $item['product_id'],
                    'car_id' => isset($item['car_id']) ? $item['car_id'] : null,
                    'room_id' => isset($item['room_id']) ? $item['room_id'] : null,
                    'variation_id' => isset($item['variation_id']) ? $item['variation_id'] : null,
                    'service_date' => $item['service_date'],
                    'quantity' => $item['quantity'],
                    'special_request' => isset($item['special_request']) ? $item['special_request'] : null,
                    'route_plan' => isset($item['route_plan']) ? $item['route_plan'] : null,
                    'pickup_location' => isset($item['pickup_location']) ? $item['pickup_location'] : null,
                    'pickup_time' => isset($item['pickup_time']) ? $item['pickup_time'] : null,
                    'dropoff_location' => isset($item['dropoff_location']) ? $item['dropoff_location'] : null,
                    'duration' => $item['duration'] ?? null,
                    'selling_price' => $item['selling_price'] ?? null,
                    'cost_price' => $item['cost_price'] ?? null,
                    'payment_method' => $item['payment_method'] ?? null,
                    'payment_status' => $item['payment_status'] ?? 'not_paid',
                    'exchange_rate' => $item['exchange_rate'] ?? null,
                    'comment' => $item['comment'] ?? null,
                    'checkin_date' => isset($item['checkin_date']) ? $item['checkin_date'] : null,
                    'checkout_date' => isset($item['checkout_date']) ? $item['checkout_date'] : null,
                    'reservation_status' => $item['reservation_status'] ?? "awaiting",
                ];

                if (isset($request->items[$key]['receipt_image'])) {
                    $receiptImage = $request->items[$key]['receipt_image'];
                    if ($receiptImage) {
                        $fileData = $this->uploads($receiptImage, 'images/');
                        $data['receipt_image'] = $fileData['fileName'];
                    }
                }

                if (isset($request->items[$key]['customer_attachment'])) {
                    $attachment = $request->items[$key]['customer_attachment'];
                    $fileData = $this->uploads($attachment, 'attachments/');
                    $data['customer_attachment'] = $fileData['fileName'];
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

    public function printReceipt(Request $request, string $id)
    {
        if ($request->query('paid') && $request->query('paid') === 1) {
            $booking = Booking::where('id', $id)->with(['customer', 'items' => function ($q) {
                $q->where('payment_status', 'fully_paid');
            }, 'createdBy'])->first();
        } else {
            $booking = Booking::where('id', $id)->with(['customer', 'items', 'createdBy'])->first();
        }

        $data = new BookingResource($booking);
        $pdf = Pdf::setOption([
            'fontDir' => public_path('/fonts')
        ])->loadView('pdf.booking_receipt', compact('data'));

        return $pdf->stream();
        // return $pdf->download($booking->crm_id . '_receipt.pdf');
    }
}
