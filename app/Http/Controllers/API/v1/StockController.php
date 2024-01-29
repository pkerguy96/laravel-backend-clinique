<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StockRequest;
use App\Http\Resources\V1\StockCollection;
use App\Http\Resources\V1\StockResource;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new StockCollection(Stock::orderby('id', 'desc')->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StockRequest $request)
    {
        try {

            $data = new StockResource(Stock::create($request->all()));

            // If the patient is successfully created, return a success response
            return response()->json([
                'message' => 'product added successfully',
                'data' => $data
            ], 201); // 201 Created status code for successful resource creation
        } catch (\Exception $e) {
            // If there's an error while creating the patient, return an error response
            return response()->json([
                'message' => 'Failed to create product',
                'error' => $e->getMessage(),
            ], 500); // 500 Internal Server Error status code for server-side errors
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
