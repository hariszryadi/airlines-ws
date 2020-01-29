<?php

namespace App\Http\Controllers;

use App\Models\Airline;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookingsController extends Controller
{
    /**
     * Displaying a listing of the resource.
     *
     * @return JSON response
     */
    public function index()
    {
        $bookings = Booking::with('flight.airline')->get();

        return response()->json($bookings, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JSON response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $validationRules = [
                'user_id' => 'required|integer|exists:users,id',
                'booking_code' => 'required|numeric|digits:4|unique:bookings',
                'flight_id' => 'required|integer|exists:flights,id',
                'quantity' => 'required|numeric'
            ];
            $validator = Validator::make($input, $validationRules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $bookings = Booking::create($input);

            return response()->json([
                'data' => $bookings,
                'messages' => 'Create data successfully',
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => [],
                'messages' => 'Error server. Please contact your administrator',
                'status' => 400
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JSON response
     */
    public function show($id)
    {
        $bookings = Booking::with('flight.airline')->where('id', '=', $id)->get();
        if (!$bookings) {
            return response()->json([
                'messages' => 'request not found',
                'success' => false,
                'status' => 404
            ], 404);
        }

        return response()->json($bookings, 200);
    }

    /**
     * Update the specified resource in storage
     *
     * @param Request $request
     * @param int $id
     * @return JSON response
     */
    public function update(Request $request, $id)
    {
        if (Gate::denies('update')) {
            return response()->json([
                'messages' => 'You are unauthorized',
                'success' => false,
                'status' => 403
            ], 403);
        }
        try {
            $input = $request->all();
            $bookings = Booking::find($id);
            if (!$bookings) {
                return response()->json([
                    'messages' => 'request not found',
                    'success' => false,
                    'status' => 404
                ], 404);
            }
            $validationRules = [
                'user_id' => 'required|integer|exists:users,id',
                'booking_code' => 'required|numeric|digits:4|unique:bookings,booking_code,' . $bookings->id,
                'flight_id' => 'required|integer|exists:flights,id',
                'quantity' => 'required|numeric'
            ];
            $validator = Validator::make($input, $validationRules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $bookings->fill($input);
            $bookings->save();
    
            return response()->json([
                'data' => $bookings,
                'messages' => 'Update data successfully',
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => [],
                'messages' => 'Error server. Please contact your administrator',
                'status' => 400
            ]);
        }
    }

    /**
     * Remove the specified resource from storage
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        if (Gate::denies('delete')) {
            return response()->json([
                'messages' => 'You are unauthorized',
                'success' => false,
                'status' => 403
            ], 403);
        }
        $bookings = Booking::find($id);
        if (!$bookings) {
            return response()->json([
                'messages' => 'request not found',
                'success' => false,
                'status' => 404
            ], 404);
        }
        $bookings->delete();

        return response()->json([
            'messages' => 'delete successfully',
            'success' => true,
            'status' => 200
        ], 200);
    }
}