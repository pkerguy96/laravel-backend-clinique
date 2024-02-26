<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreNurseRequest;
use App\Http\Resources\V1\NurseCollection;
use App\Http\Resources\V1\NurseResource;

use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class NurseController extends Controller
{
    use HttpResponses;
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
        if ($authenticatedUserId->role === 'nurse') {
            return $this->error(null, 'Only doctors can create nurses!', 401);
        }
        $attributes = $request->all();
        $attributes = $request->except('checkbox');
        $attributes['doctor_id'] = $authenticatedUserId->id;
        $attributes['password'] = Hash::make($attributes['password']);
        $attributes['role'] = 'nurse';
        if ($request->input('checkbox') === true) {

            $attributes['termination_date'] = null;
        } else {

            $attributes['termination_date'] = $request->input('termination_date');
        }
        try {
            $nurseCount = User::where('doctor_id', $authenticatedUserId->id)
                ->where('role', 'nurse')
                ->count();

            if ($nurseCount >= 6) {
                return response()->json(['message' => "Vous ne pouvez avoir que jusqu'à six infirmières."], 400);
            }
            $data = new NurseResource(User::create($attributes));
            return response()->json([
                'message' => 'Nurse created successfully',
                'data' => $data
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create Nurse',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        //TODO: add nurses lol
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
