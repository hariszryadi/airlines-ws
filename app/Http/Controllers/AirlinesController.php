<?php

namespace App\Http\Controllers;

use App\Models\Airline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class AirlinesController extends Controller
{
    /**
     * Displaying a listing of the resource.
     *
     * @return JSON response
     */
    public function index()
    {
        $airlines = Airline::all();
        
        return response()->json($airlines, 200);
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
                'airline_name' => 'required|string',
                'type' => 'required|string',
                'price' => 'required|numeric'
            ];
            $validator = Validator::make($input, $validationRules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $airlines = Airline::create($input);
            
            return response()->json([
                'data' => $airlines,
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
        $airlines = Airline::find($id);
        if (!$airlines) {
            return response()->json([
                'messages' => 'request not found',
                'success' => false,
                'status' => 404
            ], 404);
        }

        return response()->json($airlines, 200);
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
            $airlines = Airline::find($id);
            if (!$airlines) {
                return response()->json([
                    'messages' => 'request not found',
                    'success' => false,
                    'status' => 404
                ], 404);
            }
            $validationRules = [
                'airline_name' => 'required|string',
                'type' => 'required|string',
                'price' => 'required|numeric'
            ];
            $validator = Validator::make($input, $validationRules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $airlines->fill($input);
            $airlines->save();
    
            return response()->json([
                'data' => $airlines,
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
        $airlines = Airline::find($id);
        if (!$airlines) {
            return response()->json([
                'messages' => 'request not found',
                'success' => false,
                'status' => 404
            ], 404);
        }
        $airlines->delete();

        return response()->json([
            'messages' => 'delete successfully',
            'success' => true,
            'status' => 200
        ], 200);
    }
}