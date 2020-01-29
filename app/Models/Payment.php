<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'booking_id', 
        'payment_method', 
        'date_purchased'
    ];

    public $timestamps = false;

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function flight()
    {
        return $this->belongsTo(Flight::class, 'flight_id');
    }
}