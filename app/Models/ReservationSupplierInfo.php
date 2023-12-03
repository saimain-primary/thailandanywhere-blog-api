<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationSupplierInfo extends Model
{
    use HasFactory;

    protected $fillable = ['booking_item_id', 'supplier_name', 'ref_number', 'booking_confirm_letter'];

}
