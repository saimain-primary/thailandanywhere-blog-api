<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationInfo extends Model
{
    use HasFactory;

    protected $fillable = ['booking_item_id', 'customer_feedback', 'customer_score', 'special_request', 'other_info','route_plan','pickup_location'];
}
