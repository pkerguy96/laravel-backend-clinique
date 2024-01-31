<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Payement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardKpisController extends Controller
{
    public function getAppointments()
    {
        $data =  Appointment::all()->count();
        return response()->json(['data' => $data]);
    }
    public function getCanceledAppointments()
    {
        $data = Appointment::withTrashed()->whereNotNull('deleted_at')->count();
        return response()->json(['data' => $data]);
    }
    public function getTotalRevenue()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        $data1 = [];
        $data2 = [];

        for ($month = 1; $month <= 12; $month++) {
            if ($currentMonth >= $month) {

                $currentMonthRevenue = Payement::whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $month)
                    ->sum('total_cost');
                $data1[] = $currentMonthRevenue;

                // Get revenue for the previous months
                $previousMonthRevenue = Payement::whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $month - 1) // Calculate for the previous month
                    ->sum('total_cost');
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
        $ageGroups = Patient::selectRaw('CASE 
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
        $data = Patient::all()->count();
        return response()->json(['data' => $data]);
    }
}
