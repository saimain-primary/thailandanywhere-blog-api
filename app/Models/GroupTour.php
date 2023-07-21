<?php

namespace App\Models;

use App\Models\City;
use App\Models\ProductTag;
use App\Models\Destination;
use App\Models\GroupTourImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupTour extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sku_code', 'description', 'price', 'cover_image', 'cancellation_policy_id'];

    public function images()
    {
        return $this->hasMany(GroupTourImage::class, 'group_tour_id', 'id');
    }

    public function destinations()
    {
        return $this->belongsToMany(Destination::class, 'group_tour_destinations', 'group_tour_id', 'destination_id');
    }

    public function tags()
    {
        return $this->belongsToMany(ProductTag::class, 'group_tour_tags', 'group_tour_id', 'product_tag_id');
    }

    public function cities()
    {
        return $this->belongsToMany(City::class, 'group_tour_cities', 'group_tour_id', 'city_id');
    }
}
