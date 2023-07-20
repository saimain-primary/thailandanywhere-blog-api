<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrivateVanTour extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable  = ['name', 'description', 'long_description', 'cover_image','sku_code'];

    public function tags()
    {
        return $this->belongsToMany(ProductTag::class, 'private_van_tour_tags', 'private_van_tour_id', 'product_tag_id');
    }
}
