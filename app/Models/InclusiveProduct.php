<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InclusiveProduct extends Model
{
    use HasFactory;

    protected $fillable = ['inclusive_id', 'product_type', 'product_id', 'car_id'];
}
