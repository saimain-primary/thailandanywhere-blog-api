<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InclusiveAirlineTicket extends Model
{
    use HasFactory;

    protected $fillable = ['inclusive_id', 'product_id', 'ticket_id', 'cost_price', 'selling_price', 'quantity'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(EntranceTicket::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(AirlineTicket::class);
    }
}
