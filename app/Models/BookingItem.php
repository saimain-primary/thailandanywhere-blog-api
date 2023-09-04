<?php

namespace App\Models;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingItem extends Model
{
    use HasFactory;

    protected $fillable = ['route_plan','pickup_location','dropoff_location', 'booking_id','special_request', 'car_id', 'product_type', 'product_id', 'service_date', 'quantity', 'duration', 'selling_price', 'comment', 'reservation_status', 'receipt_image', 'cost_price', 'payment_status', 'payment_method', 'confirmation_letter', 'exchange_rate' , 'variation_id'];

    public function product()
    {
        return $this->morphTo();
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function variation()
    {
        return $this->belongsTo(EntranceTicketVariation::class);
    }


    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function reservationInfo()
    {
        return $this->hasOne(ReservationInfo::class, 'booking_item_id');
    }

    public function reservationCarInfo()
    {
        return $this->hasOne(ReservationCarInfo::class, 'booking_item_id');
    }
}
