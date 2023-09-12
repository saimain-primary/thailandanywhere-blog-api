<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntranceTicketVariation extends Model
{
    use HasFactory;

    protected $fillable = ['entrance_ticket_id', 'name', 'age_group', 'price','description'];
}
