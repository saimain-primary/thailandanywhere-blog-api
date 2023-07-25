<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'product_type', 'product_id', 'service_date', 'quantity', 'duration', 'selling_price', 'comment', 'reservation_status', 'receipt_image'];

    public function product()
    {
        return $this->morphTo();
    }
}
