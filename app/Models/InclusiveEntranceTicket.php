<?php

namespace App\Models;

use App\Models\Car;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InclusiveEntranceTicket extends Model
{
    use HasFactory;

    protected $fillable = ['inclusive_id', 'product_id', 'car_id'];

    public function product()
    {
        return $this->belongsTo(EntranceTicket::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
