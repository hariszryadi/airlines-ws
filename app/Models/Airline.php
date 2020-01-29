<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    protected $table = 'airlines';

    protected $fillable = ['airline_name', 'type', 'price'];

    public $timestamps = false;

    public function flight()
    {
        return $this->hasOne(Flight::class, 'airline_id');
    }
}