<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InclusiveGroupTour extends Model
{
    use HasFactory;

    protected $fillable = ['inclusive_id', 'product_id', 'car_id', 'selling_price', 'cost_price', 'quantity','day'];

    public function product()
    {
        return $this->belongsTo(GroupTour::class);
    }
}
