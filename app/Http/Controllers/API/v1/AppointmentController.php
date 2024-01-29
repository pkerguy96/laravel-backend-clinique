<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\AppointmentRequest;
use App\Http\Resources\V1\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Resources\V1\AppointmentCollection;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new AppointmentCollection(Appointment::orderby('id', 'desc')->get());
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
    public function store(AppointmentRequest $request)
    {
        $appointment_date = $request->input('date');
        $existingAppointment = Appointment::where('date', $appointment_date)->first();
        if ($existingAppointment) {
            return response()->json([
                'message' => 'Un rendez-vous existe déjà à cette date et heure.',
            ], 422); // Return an appropriate HTTP status code (Unprocessable Entity) for validation error
        }
        $authenticatedUserId = auth()->user();
        $attributes = $request->all();
        $attributes['doctor_id'] = $authenticatedUserId->id;

        $data = new AppointmentResource(Appointment::create($attributes));

        // If the patient is successfully created, return a success response
        return response()->json([
            'message' => 'appointment created successfully',
            'data' => $data
        ], 201);
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
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        // Delete the appointment
        $appointment->delete();

        return response()->json(['message' => 'Appointment deleted'], 204);
    }
}
