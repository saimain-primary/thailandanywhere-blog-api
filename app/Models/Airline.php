<?php

namespace App\Models;

use App\Models\AirlineTicket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Airline extends Model
{
    use HasFactory;

    protected $fillable = ['name','contract','legal_name','starting_balance'];

    public function tickets(): HasMany
    {
        return $this->hasMany(AirlineTicket::class, 'airline_id', 'id');
    }
}
