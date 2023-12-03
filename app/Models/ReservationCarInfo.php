<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationCarInfo extends Model
{
    use HasFactory;

    protected $fillable = ['booking_item_id','account_holder_name', 'supplier_name', 'driver_name', 'driver_contact', 'car_number', 'car_photo'];
}
