<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\OperationPreferenceRequest;
use App\Http\Resources\V1\OperationPreferenceResource;
use App\Models\OperationPreference;
use App\Models\UserPreference;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends Controller
{
    use HttpResponses;
    public function DashboardKpiUserPref(Request $request)
    {
        if (!$request->input('period')) {
            return $this->error(null, 'Veuillez sélectionner une période', 501);
        }
        $user = Auth::user();
        $doctorId = ($user->role === 'nurse') ? $user->doctor_id : $user->id;
        if (!$user) {
            return $this->error(null, 'Aucun utilisateur trouvé', 501);
        }
        UserPreference::where('doctor_id', $doctorId)->update([
            'kpi_date' => $request->input('period'),
        ]);
        return $this->success('success', 'La préférence a été modifiée', 200);
    }
    public function OperationUserPref(OperationPreferenceRequest $request)
    {

        try {
            $user = Auth::user();
            $doctorId = ($user->role === 'nurse') ? $user->doctor_id : $user->id;
            $data = $request->all();
            $data['doctor_id'] = $doctorId;
            $operation =  new OperationPreferenceResource(OperationPreference::create($data));

            return $this->success($operation, 'Insertion réussie', 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->error($th, 'error', 501);
        }
    }
    public function getOperationPrefs()
    {

        $user = Auth::user();
        $doctorId = ($user->role === 'nurse') ? $user->doctor_id : $user->id;
        $operations = OperationPreference::where('doctor_id', $doctorId)->get();
        return  OperationPreferenceResource::collection($operations);
    }
    public function deleteOperationPrefs($id)
    {
        $user = Auth::user();

        // Ensure user is authenticated
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Ensure the user is authorized to delete this operation preference
        $doctorId = ($user->role === 'nurse') ? $user->doctor_id : $user->id;
        $operationPreference = OperationPreference::where('doctor_id', $doctorId)->where('id', $id)->first();

        if (!$operationPreference) {
            return response()->json(['error' => 'Operation preference not found'], 404);
        }

        // Delete the operation preference
        $operationPreference->delete();

        // Respond with a success message
        return response()->json(['message' => 'Operation preference deleted successfully'], 200);
    }
}
