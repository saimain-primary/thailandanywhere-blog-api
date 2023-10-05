<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\BookingReceipt;
use App\Models\ReservationPaidSlip;
use App\Traits\ImageManager;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\BookingItemResource;
use App\Http\Resources\BookingResource;
use App\Models\ReservationBookingConfirmLetter;
use App\Models\ReservationCarInfo;
use App\Models\ReservationCustomerPassport;
use App\Models\ReservationExpenseReceipt;
use App\Models\ReservationInfo;
use App\Models\ReservationSupplierInfo;

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
        $filter = $request->query('filter');


        $query = Booking::query();

        if (Auth::user()->role === 'super_admin') {
            if ($filter) {
                if ($filter === 'all') {
                } elseif ($filter === 'past') {
                    $query->where('is_past_info', true)->whereNotNull('past_user_id');
                } elseif ($filter === 'current') {
                    $query->whereNull('past_user_id');
                }
            }
        } else {

            $query->where('created_by', Auth::id())->orWhere('past_user_id', Auth::id());

            if ($filter) {
                if ($filter === 'all') {
                    $query->where('created_by', Auth::id())->orWhere('past_user_id', Auth::id());
                } elseif ($filter === 'past') {
                    $query->where('is_past_info', true)->where('past_user_id', Auth::id())->whereNotNull('past_user_id');
                } elseif ($filter === 'current') {
                    $query->where('created_by', Auth::id())->whereNull('past_user_id');
                }
            }
        }

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }


        $query->orderBy('created_at', 'desc');

        $productType = $request->query('product_type');
        $crmId = $request->query('crm_id');

        if ($productType) {
            $query->whereHas('items', function ($q) use ($productType, $crmId) {
                if ($crmId) {
                    $q->where('crm_id', 'LIKE', "%{$crmId}%");
                }
                $q->where('product_type', $productType);

            })->with(['items' => function ($query) use ($productType, $crmId) {
                if ($crmId) {
                    $query->where('crm_id', 'LIKE', "%{$crmId}%");
                }
                $query->where('product_type', $productType);
            }]);
        } else {
            $query->whereHas('items', function ($q) use ($crmId) {
                if ($crmId) {
                    $q->where('crm_id', 'LIKE', "%{$crmId}%");
                }
            })->with(['items' => function ($query) use ($crmId) {
                if ($crmId) {
                    $query->where('crm_id', 'LIKE', "%{$crmId}%");
                }
            }]);
        }

        $data = $query->paginate($limit);

        return $this->success(BookingResource::collection($data)
            ->additional([
                'meta' => [
                    'total_page' => (int)ceil($data->total() / $data->perPage()),
                ],
            ])
            ->response()
            ->getData(), 'Reservation List');
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
            'expense_amount' => $request->expense_amount ?? $find->expense_amount,
            'comment' => $request->comment ?? $find->comment,
        ];


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

    public function updateInfo(Request $request, $id)
    {
        $bookingItem = BookingItem::find($id);

        if (!$bookingItem) {
            return $this->error(null, 'Data not found', 404);
        }

        $findInfo = ReservationInfo::where('booking_item_id', $bookingItem->id)->first();
        if (!$findInfo) {

            $saveData = [
                'booking_item_id' => $bookingItem->id,
                'customer_feedback' => $request->customer_feedback,
                'customer_score' => $request->customer_score,
                'driver_score' => $request->driver_score,
                'product_score' => $request->product_score,
                'special_request' => $request->special_request,
                'other_info' => $request->other_info,
                'route_plan' => $request->route_plan,
                'pickup_location' => $request->pickup_location,
                'payment_method' => $request->payment_method,
                'bank_name' => $request->bank_name,
                'bank_account_number' => $request->bank_account_number,
                'expense_amount' => $request->expense_amount,
                'cost' => $request->cost,
                'payment_status' => $request->payment_status,
                'payment_due' => $request->payment_due,
            ];

            //            if ($file = $request->file('paid_slip')) {
            //                $fileData = $this->uploads($file, 'images/');
            //                $saveData['paid_slip'] = $fileData['fileName'];
            //            }


            $save = ReservationInfo::create($saveData);

            if ($request->paid_slip) {
                foreach ($request->paid_slip as $image) {
                    $fileData = $this->uploads($image, 'images/');
                    ReservationPaidSlip::create(['booking_item_id' => $save->booking_item_id, 'file' => $fileData['fileName']]);
                }
            }


            if ($request->customer_passport) {
                foreach ($request->customer_passport as $passport) {
                    $fileData = $this->uploads($passport, 'files/');
                    ReservationCustomerPassport::create(['booking_item_id' => $findInfo->booking_item_id, 'file' => $fileData['fileName']]);
                }
            }


        } else {
            $findInfo->customer_feedback = $request->customer_feedback ?? $findInfo->customer_feedback;
            $findInfo->customer_score = $request->customer_score ?? $findInfo->customer_score;
            $findInfo->driver_score = $request->driver_score ?? $findInfo->driver_score;
            $findInfo->product_score = $request->product_score ?? $findInfo->product_score;
            $findInfo->special_request = $request->special_request ?? $findInfo->special_request;
            $findInfo->other_info = $request->other_info ?? $findInfo->other_info;
            $findInfo->route_plan = $request->route_plan ?? $findInfo->route_plan;
            $findInfo->pickup_location = $request->pickup_location ?? $findInfo->pickup_location;
            $findInfo->payment_method = $request->payment_method ?? $findInfo->payment_method;
            $findInfo->payment_status = $request->payment_status ?? $findInfo->payment_status;
            $findInfo->payment_due = $request->payment_due ?? $findInfo->payment_due;
            $findInfo->payment_receipt = $request->payment_receipt ?? $findInfo->payment_receipt;
            $findInfo->expense_amount = $request->expense_amount ?? $findInfo->expense_amount;
            $findInfo->bank_name = $request->bank_name ?? $findInfo->bank_name;
            $findInfo->cost = $request->cost ?? $findInfo->cost;
            $findInfo->bank_account_number = $request->bank_account_number ?? $findInfo->bank_account_number;

            //            if ($file = $request->file('paid_slip')) {
            //                $fileData = $this->uploads($file, 'images/');
            //                $findInfo->paid_slip = $fileData['fileName'];
            //            }

            $findInfo->update();

            if ($request->paid_slip) {
                foreach ($request->paid_slip as $image) {
                    $fileData = $this->uploads($image, 'images/');
                    ReservationPaidSlip::create(['booking_item_id' => $findInfo->booking_item_id, 'file' => $fileData['fileName']]);
                }
            }
        }


        $isEntranceTicketType = $bookingItem->product_type === 'App\Models\EntranceTicket';
        $isHotelType = $bookingItem->product_type === 'App\Models\Hotel';

        if (!$isEntranceTicketType && !$isHotelType) {
            $findCarInfo = ReservationCarInfo::where('booking_item_id', $bookingItem->id)->first();
            if (!$findCarInfo) {
                $data = [
                    'booking_item_id' => $bookingItem->id,
                    'driver_name' => $request->driver_name,
                    'driver_contact' => $request->driver_contact,
                    'account_holder_name' => $request->account_holder_name,
                    'supplier_name' => $request->supplier_name,
                    'car_number' => $request->car_number,
                ];

                if ($file = $request->file('car_photo')) {
                    $fileData = $this->uploads($file, 'images/');
                    $data['car_photo'] = $fileData['fileName'];
                }
                ReservationCarInfo::create($data);
            } else {

                $findCarInfo->driver_name = $request->driver_name ?? $findCarInfo->driver_name;
                $findCarInfo->driver_contact = $request->driver_contact ?? $findCarInfo->driver_contact;
                $findCarInfo->supplier_name = $request->supplier_name ?? $findCarInfo->supplier_name;
                $findCarInfo->car_number = $request->car_number ?? $findCarInfo->car_number;
                $findCarInfo->account_holder_name = $request->account_holder_name ?? $findCarInfo->account_holder_name;

                if ($file = $request->file('car_photo')) {
                    if ($findCarInfo->car_photo) {
                        Storage::delete('public/images/' . $findCarInfo->car_photo);
                    }
                    $fileData = $this->uploads($file, 'images/');
                    $findCarInfo->car_photo = $fileData['fileName'];
                }


                $findCarInfo->update();
            }

            if ($request->receipt_image) {
                foreach ($request->receipt_image as $image) {
                    $fileData = $this->uploads($image, 'images/');
                    ReservationExpenseReceipt::create(['booking_item_id' => $findInfo->booking_item_id, 'file' => $fileData['fileName']]);
                }
            }

        } else {
            $findInfo = ReservationSupplierInfo::where('booking_item_id', $bookingItem->id)->first();
            if (!$findInfo) {
                $data = [
                    'booking_item_id' => $bookingItem->id,
                    'ref_number' => $request->ref_number,
                    'supplier_name' => $request->supplier_name,
                ];

                //                if ($file = $request->file('receipt_image')) {
                //                    $fileData = $this->uploads($file, 'images/');
                //                    ReservationExpenseReceipt::create(['booking_item_id' => $bookingItem->id, 'file' => $fileData['fileName']]);
                //                }

                if ($request->receipt_image) {
                    foreach ($request->receipt_image as $image) {
                        $fileData = $this->uploads($image, 'images/');
                        ReservationExpenseReceipt::create(['booking_item_id' => $bookingItem->id, 'file' => $fileData['fileName']]);
                    }
                }

                if ($file = $request->file('booking_confirm_letter')) {
                    $fileData = $this->uploads($file, 'images/');
                    ReservationBookingConfirmLetter::create(['booking_item_id' => $bookingItem->id, 'file' => $fileData['fileName']]);
                }


                ReservationSupplierInfo::create($data);
            } else {

                $findInfo->ref_number = $request->ref_number ?? $findInfo->ref_number;
                $findInfo->supplier_name = $request->supplier_name ?? $findInfo->supplier_name;

                //                if ($file = $request->file('receipt_image')) {
                //                    $fileData = $this->uploads($file, 'images/');
                //                    ReservationExpenseReceipt::create(['booking_item_id' => $findInfo->booking_item_id, 'file' => $fileData['fileName']]);
                //                }

                if ($request->receipt_image) {
                    foreach ($request->receipt_image as $image) {
                        $fileData = $this->uploads($image, 'images/');
                        ReservationExpenseReceipt::create(['booking_item_id' => $findInfo->booking_item_id, 'file' => $fileData['fileName']]);
                    }
                }

                if ($file = $request->file('booking_confirm_letter')) {
                    $fileData = $this->uploads($file, 'images/');
                    ReservationBookingConfirmLetter::create(['booking_item_id' => $findInfo->booking_item_id, 'file' => $fileData['fileName']]);
                }


                $findInfo->update();
            }
        }

        if ($request->customer_passport) {
            foreach ($request->customer_passport as $passport) {
                $fileData = $this->uploads($passport, 'files/');
                ReservationCustomerPassport::create(['booking_item_id' => $findInfo->booking_item_id, 'file' => $fileData['fileName']]);
            }
        }

        return $this->success(new BookingItemResource($bookingItem), 'Successfully updated');
    }

    public function deleteReceipt($id)
    {
        $find = ReservationExpenseReceipt::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        Storage::delete('public/images/' . $find->file);
        $find->delete();
        return $this->success(null, 'Successfully deleted');

    }

    public function deleteConfirmationReceipt($id)
    {
        $find = ReservationPaidSlip::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        Storage::delete('public/images/' . $find->file);
        $find->delete();
        return $this->success(null, 'Successfully deleted');


    }

    public function deleteCustomerPassport($id)
    {
        $find = ReservationCustomerPassport::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        Storage::delete('public/files/' . $find->file);
        $find->delete();
        return $this->success(null, 'Successfully deleted');


    }
}
