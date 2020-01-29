<?php

namespace App\Http\Controllers;

use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PassengersController extends Controller
{
    /**
     * Displaying a listing of the resource.
     *
     * @return JSON response
     */
    public function index()
    {
        $passengers = Passenger::all();

        return response()->json($passengers, 200);
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
                'name' => 'required|min:2|max:30',
                'nik' => 'required|numeric|digits:15'
            ];
            $validator = Validator::make($input, $validationRules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $passengers = Passenger::create($input);
    
            return response()->json([
                'data' => $passengers,
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
        $passengers = Passenger::find($id);
        if (!$passengers) {
            return response()->json([
                'messages' => 'request not found',
                'success' => false,
                'status' => 404
            ], 404);
        }

        return response()->json($passengers, 200);
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
            $passengers = Passenger::find($id);
            if (!$passengers) {
                return response()->json([
                    'messages' => 'request not found',
                    'success' => false,
                    'status' => 404
                ], 404);
            }
            $validationRules = [
                'booking_id' => 'required|integer|exists:bookings,id',
                'name' => 'required|min:2|max:30',
                'nik' => 'required|numeric|digits:15'
            ];
            $validator = Validator::make($input, $validationRules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $passengers->fill($input);
            $passengers->save();
    
            return response()->json([
                'data' => $passengers,
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
        $passengers = Passenger::find($id);
        if (!$passengers) {
            return response()->json([
                'messages' => 'request not found',
                'success' => false,
                'status' => 404
            ], 404);
        }
        $passengers->delete();

        return response()->json([
            'messages' => 'delete successfully',
            'success' => true,
            'status' => 200
        ], 200);
    }
}