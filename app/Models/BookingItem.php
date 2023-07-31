<?php

namespace App\Models;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingItem extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'car_id', 'product_type', 'product_id', 'service_date', 'quantity', 'duration', 'selling_price', 'comment', 'reservation_status', 'receipt_image', 'cost_price', 'payment_status', 'payment_method', 'confirmation_letter', 'exchange_rate'];

    public function product()
    {
        return $this->morphTo();
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
