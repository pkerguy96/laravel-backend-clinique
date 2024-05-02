<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WaitingRoom;
use Illuminate\Support\Facades\Log;

class WaitingRoomController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        try {
            $user = Auth::user();
            $doctorId = ($user->role === 'nurse') ? $user->doctor_id : $user->id;
            $patientswaiting = WaitingRoom::where('doctor_id', $doctorId)->pluck('num_patients_waiting');
            return $this->success($patientswaiting, 'success', 201);
        } catch (\Throwable $th) {
            $this->error($th->getMessage(), 'oops', 500);
        }
    }
    public function addPatient()
    {
        try {
            $user = Auth::user();
            $doctorId = ($user->role === 'nurse') ? $user->doctor_id : $user->id;

            // Increment the number of patients waiting by 1
            WaitingRoom::where('doctor_id', $doctorId)->increment('num_patients_waiting');

            return $this->success('Patient added successfully', 'success', 201);
        } catch (\Throwable $th) {
            $this->error($th->getMessage(), 'oops', 500);
        }
    }

    public function removePatient()
    {
        try {
            $user = Auth::user();
            $doctorId = ($user->role === 'nurse') ? $user->doctor_id : $user->id;

            // Decrement the number of patients waiting by 1
            WaitingRoom::where('doctor_id', $doctorId)->decrement('num_patients_waiting');

            return $this->success('Patient removed successfully', 'success', 201);
        } catch (\Throwable $th) {
            $this->error($th->getMessage(), 'oops', 500);
        }
    }
    public function resetPatientCounter()
    {
        try {
            $user = Auth::user();
            $doctorId = ($user->role === 'nurse') ? $user->doctor_id : $user->id;

            // Decrement the number of patients waiting by 1
            WaitingRoom::where('doctor_id', $doctorId)->update(['num_patients_waiting' => 0]);

            return $this->success('Patient counter reseted successfully', 'success', 201);
        } catch (\Throwable $th) {
            $this->error($th->getMessage(), 'oops', 500);
        }
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
    public function store(Request $request)
    {
        //
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
    }
}
