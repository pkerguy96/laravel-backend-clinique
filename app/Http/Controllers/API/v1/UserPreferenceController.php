<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
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
        if (!$user) {
            return $this->error(null, 'Aucun utilisateur trouvé', 501);
        }
        UserPreference::where('doctor_id', $user->id)->update([
            'kpi_date' => $request->input('period'),
        ]);
        return $this->success('success', 'La préférence a été modifiée', 200);
    }
}
