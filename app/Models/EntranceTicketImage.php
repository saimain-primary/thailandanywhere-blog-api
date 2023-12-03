<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntranceTicketImage extends Model
{
    use HasFactory;

    protected $fillable = ['entrance_ticket_id', 'image'];
}
