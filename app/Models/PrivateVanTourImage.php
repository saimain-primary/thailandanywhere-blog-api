<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateVanTourImage extends Model
{
    use HasFactory;

    protected $fillable = ['private_van_tour_id', 'image'];
}
