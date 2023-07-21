<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirportPickupImage extends Model
{
    use HasFactory;

    protected $fillable = ['airport_pickup_id', 'image'];
}
