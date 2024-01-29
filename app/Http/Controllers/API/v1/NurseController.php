<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreNurseRequest;
use App\Http\Resources\V1\NurseCollection;
use App\Http\Resources\V1\NurseResource;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class NurseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctor_id = Auth()->id();
        return new NurseCollection(User::where('doctor_id', $doctor_id)->orderby('id', 'desc')->get());
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
    public function store(StoreNurseRequest $request)
    {

        $authenticatedUserId = auth()->user();
        $attributes = $request->all();
        $attributes['doctor_id'] = $authenticatedUserId->id;
        $attributes['password'] = Hash::make($attributes['password']);
        $attributes['role'] = 'nurse';
        try {
            $nurseCount = User::where('doctor_id', $authenticatedUserId->id)
                ->where('role', 'nurse')
                ->count();

            if ($nurseCount >= 6) {
                return response()->json(['message' => "Vous ne pouvez avoir que jusqu'à six infirmières."], 400);
            }

            // Attempt to create a new patient based on the validated request data
            $data = new NurseResource(User::create($attributes));

            // If the patient is successfully created, return a success response
            return response()->json([
                'message' => 'Nurse created successfully',
                'data' => $data
            ], 201); // 201 Created status code for successful resource creation
        } catch (\Exception $e) {
            // If there's an error while creating the Nurse, return an error response
            return response()->json([
                'message' => 'Failed to create Nurse',
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
