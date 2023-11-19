<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InclusivePrivateVanTour extends Model
{
    use HasFactory;

    protected $fillable = ['inclusive_id', 'product_id', 'car_id','selling_price','cost_price','quantity','day'];


    public function product()
    {
        return $this->belongsTo(PrivateVanTour::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
