<?php

namespace App\Models;

use App\Models\Car;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InclusiveEntranceTicket extends Model
{
    use HasFactory;

    protected $fillable = ['inclusive_id', 'product_id', 'variation_id'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(EntranceTicket::class);
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(EntranceTicketVariation::class);
    }
}
