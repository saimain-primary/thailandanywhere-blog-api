<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\BookingItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_number', 'bank_name','crm_id', 'customer_id', 'sold_from', 'payment_method', 'payment_currency', 'payment_status', 'booking_date', 'money_exchange_rate', 'discount', 'sub_total', 'grand_total', 'deposit', 'balance_due', 'balance_due_date', 'comment', 'reservation_status', 'created_by'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(BookingItem::class);
    }

    public function receipts()
    {
        return $this->hasMany(BookingReceipt::class);
    }


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->invoice_number = $model->generateInvoiceNumber();
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
        return static::where('invoice_number', $number)->exists();
    }

    public function generateCrmID()
    {
        // Ensure the first letter of each word is capitalized
        $user = Auth::user();
        $name = ucwords(strtolower($user->name));

        // Use a regex pattern to get the first letter of each word
        preg_match_all('/\b\w/', $name, $matches);

        // Return combined initials
        $combined = implode('', $matches[0])  . rand(0, 9);
        ;
        // Count previous bookings for the user
        $previousBookingsCount = static::where('created_by', $user->id)->count();

        // Construct the booking ID
        $bookingId = $combined . '-' . str_pad($previousBookingsCount + 1, 4, '0', STR_PAD_LEFT);

        return $bookingId;
    }


}
