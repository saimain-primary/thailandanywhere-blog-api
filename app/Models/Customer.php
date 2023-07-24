<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone_number', 'dob', 'nrc_number', 'line_id', 'company_name', 'comment', 'photo','is_corporate_customer'];

    protected $casts = [
        'is_corporate_customer' => 'boolean'
    ];
}
