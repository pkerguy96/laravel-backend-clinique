<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Http\Requests\V1\StorePatientRequest;
use App\Http\Resources\V1\PatientResource;
use App\Http\Resources\V1\PatientCollection;
use App\Http\Resources\V1\PatientDetailResource;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //TODO: CIN FIX CHECK AGE IF REQUIRED OR NOT 
    public function index()
    {
        $doctor_id = Auth()->id();

        return new PatientCollection(Patient::with('appointments', 'Ordonance')->where('doctor_id', $doctor_id)->orderBy('id', 'desc')->get());
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
    public function store(StorePatientRequest $request)
    {
        try {
            // Attempt to create a new patient based on the validated request data
            $doctor_id = Auth()->id();

            $requestData = $request->all();
            $requestData['doctor_id'] = $doctor_id;

            $data = new PatientResource(Patient::create($requestData));

            // If the patient is successfully created, return a success response
            return response()->json([
                'message' => 'Patient created successfully',
                'data' => $data
            ], 201); // 201 Created status code for successful resource creation
        } catch (\Exception $e) {
            // If there's an error while creating the patient, return an error response
            return response()->json([
                'message' => 'Failed to create patient',
                'error' => $e->getMessage(),
            ], 500); // 500 Internal Server Error status code for server-side errors
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $doctor_id = Auth()->id();
        return  new PatientResource(Patient::where('id', $id)->where('doctor_id', $doctor_id)->first());
    }
    public function patientDetails(string $id)
    {
        $doctor_id = Auth()->id();
        return  new PatientDetailResource(Patient::with('appointments', 'operations')->where('id', $id)->where('doctor_id', $doctor_id)->first());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePatientRequest $request, string $id)
    {
        $doctor_id = Auth()->id();
        $patient = Patient::where('doctor_id', $doctor_id)->findOrFail($id);

        if (!$patient) {
            return response()->json([
                'message' => 'Patient not found.',
            ], 404);
        }

        // Validate the updated data
        $validatedData = $request->validated();

        // Update patient details
        $patient->update($validatedData);

        return response()->json([
            'message' => 'Patient updated successfully.',
            'data' =>  new PatientResource($patient),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $doctor_id = Auth()->id();
        Patient::where('doctor_id', $doctor_id)->findorfail($id)->delete();
        return response()->json(['message' => 'patient deleted successfully'], 204);
    }
}
