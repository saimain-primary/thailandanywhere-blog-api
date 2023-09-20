<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntranceTicketVariation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'entrance_ticket_id','cost_price','price_name', 'price','description'];

    public function entranceTicket(): BelongsTo
    {
        return $this->belongsTo(EntranceTicket::class, 'entrance_ticket_id');
    }
}
