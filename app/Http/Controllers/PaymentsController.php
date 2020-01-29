<?php

namespace App\Http\Controllers;

use App\Models\Airline;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentsController extends Controller
{
    /**
     * Displaying a listing of the resource.
     *
     * @return JSON response
     */
    public function index()
    {
        $payments = Payment::with('booking.passenger')->get();

        return response()->json($payments, 200);
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
                'booking_id' => 'required|integer|exists:bookings,id',
                'payment_method' => 'required|in:credit_card,transfer',
                'date_purchased' => 'required|date_format:Y-m-d H:i:s'
            ];
            $validator = Validator::make($input, $validationRules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $payments = new Payment;
            $payments->booking_id = $request->input('booking_id');
            $booking = Booking::where('id', $payments->booking_id)->first();
            $payments->flight_id = $booking->flight_id;
            $payments->booking_code = $booking->booking_code;
            $payments->payment_method = $request->input('payment_method');
            $payments->date_purchased = $request->input('date_purchased');
            $flight = Flight::where('id', $payments->flight_id)->first();
            $airline = Airline::where('id', $flight->airline_id)->first();
            switch ($flight->class) {
                case 'economy':
                    $payments->total_price = $booking->quantity * $airline->price;
                    $payments->save();
                    break;
                case 'business':
                    $payments->total_price = $booking->quantity * ($airline->price + 500000);
                    $payments->save();
                    break;
                case 'first_class':
                    $payments->total_price = $booking->quantity * ($airline->price + 1000000);
                    $payments->save();
                    break;
            }
    
            return response()->json([
                'data' => $payments,
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
        $payments = Payment::with('booking.passenger')->where('id', $id)->get();
        if (!$payments) {
            return response()->json([
                'messages' => 'request not found',
                'success' => false,
                'status' => 404
            ], 404);
        }

        return response()->json($payments, 200);
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
        //
    }

    /**
     * Remove the specified resource from storage
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        //
    }
}