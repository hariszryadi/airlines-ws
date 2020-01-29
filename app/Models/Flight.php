<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $table = 'flights';

    protected $fillable = [
        'airline_id', 
        'departure_city', 
        'arrival_city',
        'departure_time',
        'arrival_time',
        'class'
    ];

    public $timestamps = false;

    public function airline()
    {
        return $this->belongsTo(Airline::class, 'airline_id');
    }

    public function booking()
    {
        return $this->hasOne(Booking::class, 'flight_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'flight_id');
    }
}