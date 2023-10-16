<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InclusiveHotel extends Model
{
    use HasFactory;

    protected $fillable = ['inclusive_id', 'product_id', 'room_id', 'cost_price', 'selling_price', 'quantity'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
