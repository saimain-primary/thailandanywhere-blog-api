<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationAssociatedCustomer extends Model
{
    use HasFactory;

    protected $fillable = ['booking_item_id', 'name','phone','passport'];

}
