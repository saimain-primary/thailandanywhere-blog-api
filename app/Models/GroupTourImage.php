<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupTourImage extends Model
{
    use HasFactory;

    protected $fillable = ['group_tour_id','image'];
}
