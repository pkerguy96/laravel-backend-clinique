<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;

use App\Http\Requests\V1\OrdonanceRequest;
use App\Http\Resources\V1\OrdonanceCollection;
use App\Http\Resources\V1\OrdonanceResource;
use App\Models\Ordonance as ModelsOrdonance;
use App\Models\OrdonanceDetails;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;

class OrdonanceController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $doctorId = ($user->role === 'nurse') ? $user->doctor_id : $user->id;
        $ordonances = ModelsOrdonance::with('OrdonanceDetails', 'Patient')->where('doctor_id', $doctorId)->orderBy('id', 'desc')->get();

        return new OrdonanceCollection($ordonances);
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
        try {

            $medicineArray = $request->medicine;

            // Start a database transaction
            DB::beginTransaction();

            $user = Auth::user();
            $doctorId = ($user->role === 'nurse') ? $user->doctor_id : $user->id;
            // Create the Ordonance record
            $ordonance = ModelsOrdonance::create([
                'doctor_id' => $doctorId,
                'patient_id' => $request->input('patient_id'),
                'date' => $request->input('date'),
            ]);
            // Validate and create OrdonanceDetails records

            foreach ($medicineArray as $medicine) {
                OrdonanceDetails::create([
                    'ordonance_id' => $ordonance->id,
                    'medicine_name' => $medicine['medicine_name'],
                    'note' => $medicine['note'],
                ]);
            }

            // Commit the transaction
            DB::commit();
            $data = new OrdonanceResource(ModelsOrdonance::with('OrdonanceDetails')->where('id', $ordonance->id)->first());
            // Return a response with the created Ordonance and OrdonanceDetails
            return response()->json([
                'message' => 'Ordonance created successfully',
                'data' => $data,
            ], 201);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();
            Log::error($e);
            // Return an error response
            return response()->json([
                'message' => 'Error creating Ordonance',

            ], 500);
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
        try {
            // Find the Ordonance record by ID
            $ordonance = ModelsOrdonance::findOrFail($id);

            $user = Auth::user();
            $doctorId = ($user->role === 'nurse') ? $user->doctor_id : $user->id;
            // Start a database transaction
            DB::beginTransaction();

            // Update the Ordonance record with the new data
            $ordonance->update([
                'doctor_id' => $doctorId,
                'patient_id' => $request->input('patient_id'),
                'date' => $request->input('date'),
                // Add any other fields you want to update
            ]);

            // Validate and update OrdonanceDetails records
            $medicineArray = $request->medicine;

            // Delete existing OrdonanceDetails records
            $ordonance->OrdonanceDetails()->delete();

            // Create new OrdonanceDetails records
            foreach ($medicineArray as $medicine) {
                $ordonance->OrdonanceDetails()->create([
                    'ordonance_id' => $ordonance->id,
                    'medicine_name' => $medicine['medicine_name'],
                    'note' => $medicine['note'],
                ]);
            }
            DB::commit();
            $data = new OrdonanceResource(ModelsOrdonance::with('OrdonanceDetails')->find($ordonance->id));
            return response()->json([
                'message' => 'Ordonance updated successfully',
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            // Return an error response
            return response()->json(['message' => 'Error updating Ordonance'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $ordonance = ModelsOrdonance::findorfail($id);

            if ($ordonance) {
                $ordonance->OrdonanceDetails()->delete();
                $ordonance->delete();
                return $this->success(null, 'Ordonance deleted successfuly', 200);
            }
        } catch (\Exception $e) {
            return $this->success(null, 'oops there is an error:' . $e->getMessage(), 500);
        }
    }
}
