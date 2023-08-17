<?php

namespace App\Models;

use App\Http\Resources\InclusiveAirportPickupResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inclusive extends Model
{
    use HasFactory;

    protected $fillable  = ['name', 'description', 'sku_code', 'price', 'agent_price', 'cover_image'];

    public function groupTours()
    {

        return $this->hasMany(InclusiveGroupTour::class);
    }

    public function entranceTickets()
    {

        return $this->hasMany(InclusiveEntranceTicket::class);
    }

    public function airportPickups()
    {

        return $this->hasMany(InclusiveAirportPickup::class);
    }

    public function privateVanTours()
    {
        return $this->hasMany(InclusivePrivateVanTour::class);
    }

    public function images()
    {
        return $this->hasMany(InclusiveImage::class);
    }
}
