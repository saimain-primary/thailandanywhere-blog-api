<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\BookingItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['crm_id', 'customer_id', 'sold_from', 'payment_method', 'payment_status', 'booking_date', 'money_exchange_rate', 'discount', 'comment'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(BookingItem::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->crm_id = $model->generateInvoiceNumber();
        });
    }

    public function generateInvoiceNumber()
    {
        $number = date('YmdHis');

        // ensure unique
        while ($this->invoiceNumberExists($number)) {
            $number = str_pad((int) $number + 1, 12, '0', STR_PAD_LEFT);
        }

        return $number;
    }

    public function invoiceNumberExists($number)
    {
        return static::where('crm_id', $number)->exists();
    }
}
