<?php

namespace App\Http\Controllers;

use App\Models\Airline;
use App\Models\Flight;

class GuestController extends Controller
{
    /**
     * Displaying a listing of the resource.
     *
     * @return JSON response
     */
    public function getAirlines()
    {
        $airlines = Airline::all();
        
        return response()->json($airlines, 200);
    }

    /**
     * Displaying a listing of the resource.
     *
     * @return JSON response
     */
    public function getFlights()
    {
        $flights = Flight::with('airline')->get();

        return response()->json($flights, 200);
    }
}