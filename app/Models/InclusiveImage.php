<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InclusiveImage extends Model
{
    use HasFactory;

    protected $fillable = ['inclusive_id','image'];
}
