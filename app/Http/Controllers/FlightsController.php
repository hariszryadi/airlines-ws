<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FlightsController extends Controller
{
    /**
     * Displaying a listing of the resource.
     *
     * @return JSON response
     */
    public function index()
    {
        $flights = Flight::with('airline')->get();

        return response()->json($flights, 200);
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
                'airline_id' => 'required|integer|exists:airlines,id',
                'departure_city' => 'required|min:2|max:50',
                'arrival_city' => 'required|min:2|max:50',
                'departure_time' => 'required|date_format:Y-m-d H:i:s',
                'arrival_time' => 'required|date_format:Y-m-d H:i:s',
                'class' => 'required|in:economy,business,first_class'
            ];
            $validator = Validator::make($input, $validationRules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $flights = Flight::create($input);
    
            return response()->json([
                'data' => $flights,
                'messages' => 'Create data successfully',
                'status' => 200
            ]);
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
        $flights = Flight::with('airline')->where('id', $id)->get();
        if (!$flights) {
            return response()->json([
                'messages' => 'request not found',
                'success' => false,
                'status' => 404
            ], 404);
        }

        return response()->json($flights, 200);
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
            $flights = Flight::find($id);
            if (!$flights) {
                return response()->json([
                    'messages' => 'request not found',
                    'success' => false,
                    'status' => 404
                ], 404);
            }
            $validationRules = [
                'airline_id' => 'required|integer|exists:airlines,id',
                'departure_city' => 'required|min:2|max:50',
                'arrival_city' => 'required|min:2|max:50',
                'departure_time' => 'required|date_format:Y-m-d H:i:s',
                'arrival_time' => 'required|date_format:Y-m-d H:i:s',
                'class' => 'required|in:economy,business,first_class'
            ];
            $validator = Validator::make($input, $validationRules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $flights->fill($input);
            $flights->save();
    
            return response()->json([
                'data' => $flights,
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
        $flights = Flight::find($id);
        if (!$flights) {
            return response()->json([
                'messages' => 'request not found',
                'success' => false,
                'status' => 404
            ], 404);
        }
        $flights->delete();

        return response()->json([
            'messages' => 'delete successfully',
            'success' => true,
            'status' => 200
        ], 200);
    }
}