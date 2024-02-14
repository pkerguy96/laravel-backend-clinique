<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\AppointmentKpi;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Payement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardKpisController extends Controller
{

    public function getAppointments()
    {
        $user = Auth::user();
        $id = ($user->role === 'doctor') ? $user->id : $user->doctor_id;
        $data =  Appointment::where('doctor_id', $id)->count();
        return response()->json(['data' => $data]);
    }
    public function getCanceledAppointments()
    {
        $user = Auth::user();
        $id = ($user->role === 'doctor') ? $user->id : $user->doctor_id;
        $data = Appointment::where('doctor_id', $id)->withTrashed()->whereNotNull('deleted_at')->count();
        return response()->json(['data' => $data]);
    }
    public function getMonthlyCanceledAppointments()
    {
        $user = Auth::user();
        $id = ($user->role === 'doctor') ? $user->id : $user->doctor_id;
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        $monthlyCanceledAppointments = [];

        for ($month = 1; $month <= $currentMonth; $month++) {
            $currentMonthCanceledAppointments = Appointment::withTrashed()
                ->where('doctor_id', $id)
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $month)
                ->onlyTrashed() // Only soft-deleted appointments
                ->count();

            $monthlyCanceledAppointments[] = $currentMonthCanceledAppointments;
        }

        return response()->json([
            'data' => $monthlyCanceledAppointments,
        ]);
    }
    public function getMonthlyAppointments()
    {
        $user = Auth::user();
        $id = ($user->role === 'doctor') ? $user->id : $user->doctor_id;
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        $monthlyAppointments = [];

        for ($month = 1; $month <= $currentMonth; $month++) {
            $currentMonthAppointments = Appointment::where('doctor_id', $id)
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $month)
                ->count();

            $monthlyAppointments[] = $currentMonthAppointments;
        }

        return response()->json([
            'data' => $monthlyAppointments,
        ]);
    }
    public function getTotalRevenue()
    {
        $user = Auth::user();
        $id = ($user->role === 'doctor') ? $user->id : $user->doctor_id;

        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        $data1 = [];
        $data2 = [];

        for ($month = 1; $month <= 12; $month++) {
            if ($currentMonth >= $month) {

                // Get revenue for the current month
                $currentMonthRevenue = User::find($id)->payments()
                    ->join('operations as o1', 'o1.id', '=', 'payements.operation_id')
                    ->where('o1.doctor_id', $id)
                    ->whereYear('payements.created_at', $currentYear)
                    ->whereMonth('payements.created_at', $month)
                    ->sum('payements.total_cost');

                $data1[] = $currentMonthRevenue;

                // Get revenue for the previous months
                $previousMonthRevenue = User::find($id)->payments()
                    ->join('operations as o2', 'o2.id', '=', 'payements.operation_id')
                    ->where('o2.doctor_id', $id)
                    ->whereYear('payements.created_at', $currentYear)
                    ->whereMonth('payements.created_at', $month - 1) // Calculate for the previous month
                    ->sum('payements.total_cost');

                $data2[] = $previousMonthRevenue;
            } else {
                $data1[] = 0;
                $data2[] = 0;
            }
        }

        return response()->json([
            'data' => [$data1, $data2],
        ]);
    }


    public function calculateAgePercentage()
    {
        $user = Auth::user();
        $id = ($user->role === 'doctor') ? $user->id : $user->doctor_id;
        $ageGroups = Patient::where('doctor_id', $id)->selectRaw('CASE 
                                WHEN TIMESTAMPDIFF(YEAR, date, CURDATE()) <= 20 THEN "0-20" 
                                WHEN TIMESTAMPDIFF(YEAR, date, CURDATE()) <= 30 THEN "21-30" 
                                WHEN TIMESTAMPDIFF(YEAR, date, CURDATE()) <= 40 THEN "31-40" 
                                WHEN TIMESTAMPDIFF(YEAR, date, CURDATE()) <= 50 THEN "41-50" 
                                WHEN TIMESTAMPDIFF(YEAR, date, CURDATE()) <= 60 THEN "51-60" 
                                ELSE "61+" 
                             END as age_group, COUNT(*) as count')
            ->groupBy('age_group')
            ->orderByRaw('CAST(SUBSTRING(age_group, 1, 2) AS SIGNED)')
            ->get();

        $totalPatients = Patient::count();

        $percentageData = $ageGroups->map(function ($group) use ($totalPatients) {

            return [
                'age_group' => $group->age_group,
                'count' => $group->count,

            ];
        });

        return response()->json(['data' => $percentageData]);
    }
    public function TotalPatients()
    {
        $user = Auth::user();
        $id = ($user->role === 'doctor') ? $user->id : $user->doctor_id;
        $data = Patient::where('doctor_id', $id)->count();
        return response()->json(['data' => $data]);
    }
    public function appointmentKpipeak()
    {
        $user = Auth::user();
        $id = ($user->role === 'doctor') ? $user->id : $user->doctor_id;
        $appointments = Appointment::where('doctor_id', $id)->latest()->with('patient')->take(5)->get();

        return response()->json([
            'data' => AppointmentKpi::collection($appointments),
        ]);
    }
}
