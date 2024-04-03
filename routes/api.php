<?php

use App\Http\Controllers\API\v1\AdminController;
use App\Http\Controllers\API\v1\AppointmentController;
use App\Http\Controllers\API\v1\AuthController;
use App\Http\Controllers\Api\v1\DashboardKpisController;
use App\Http\Controllers\API\v1\fileuploadController;
use App\Http\Controllers\API\v1\NurseController;
use App\Http\Controllers\API\v1\OperationController;
use App\Http\Controllers\API\v1\OrdonanceController;
use App\Http\Controllers\API\v1\PatientController;
use App\Http\Controllers\API\v1\RolesController;
use App\Http\Controllers\API\v1\StockController;
use App\Http\Controllers\API\v1\UserPreferenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Add this function to handle OPTIONS requests for CORS

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\API\v1'], function () {
    route::post('/login', [AuthController::class, 'login']);
    route::get('/verify-token', [AuthController::class, 'Verifytoken']);
});
route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\API\v1', 'middleware' => ['auth:sanctum', 'check.termination']], function () {
    Route::get('Admin/profile', [AdminController::class, 'getpicture']);
    Route::get('Admin/logout', [AuthController::class, 'Logout']);
    Route::post('Admin/store/profile', [AdminController::class, 'storeprofile']);
    Route::post('Admin/update/profile', [AdminController::class, 'ModifyProfile']);
    Route::get('patientDetails/{id}', [PatientController::class, 'patientDetails']);
    Route::get('getByOperationId/{id}', [OperationController::class, 'getByOperationId']);
    Route::get('uploadsInfo', [fileuploadController::class, 'uploadsInfo']);
    //Kpis
    Route::get('getTotalRevenue', [DashboardKpisController::class, 'getTotalRevenue']);
    Route::get('getAppointments', [DashboardKpisController::class, 'getAppointments']);
    Route::get('getCanceledAppointments', [DashboardKpisController::class, 'getCanceledAppointments']);
    Route::get('calculateAgePercentage', [DashboardKpisController::class, 'calculateAgePercentage']);
    Route::get('TotalPatients', [DashboardKpisController::class, 'TotalPatients']);
    Route::get('appointmentKpipeak', [DashboardKpisController::class, 'appointmentKpipeak']);
    Route::get('getMonthlyAppointments', [DashboardKpisController::class, 'getMonthlyAppointments']);
    Route::get('getMonthlyCanceledAppointments', [DashboardKpisController::class, 'getMonthlyCanceledAppointments']);
    Route::get('retrieveFromCashier', [DashboardKpisController::class, 'retrieveFromCashier']);
    Route::get('OnlyCashierNumber', [DashboardKpisController::class, 'OnlyCashierNumber']);
    Route::post('PatientsDebt', [DashboardKpisController::class, 'PatientsDebt']);
    route::post('DashboardKpiUserPref', [UserPreferenceController::class, 'DashboardKpiUserPref']);
    route::post('OperationUserPref', [UserPreferenceController::class, 'OperationUserPref']);
    route::get('getOperationPrefs', [UserPreferenceController::class, 'getOperationPrefs']);
    route::delete('deleteOperationPrefs/{id}', [UserPreferenceController::class, 'deleteOperationPrefs']);
    Route::delete('deletePaymentDetail/{id}', [OperationController::class, 'deletePaymentDetail']);
    Route::get('PayementVerificationCheckout/{id}', [OperationController::class, 'PayementVerificationCheckout']);

    /* ROLES */

    route::post('grantAccess', [RolesController::class, 'grantAccess']);
    route::get('RolesNursesList', [NurseController::class, 'RolesNursesList']);
    route::get('getRoles', [RolesController::class, 'getRoles']);
    route::post('userPermissions', [RolesController::class, 'userPermissions']);
    route::post('createRole', [RolesController::class, 'createRole']);
    route::get('getUsersViaRoles', [RolesController::class, 'getUsersViaRoles']);

    /* ROLES */




    route::apiResource('Patient', PatientController::class);
    Route::apiResource('Nurse', NurseController::class);
    Route::apiResource('Appointment', AppointmentController::class);
    Route::apiResource('Stock', StockController::class);
    Route::apiResource('Ordonance', OrdonanceController::class);
    Route::apiResource('Operation', OperationController::class);
    Route::apiResource('Filesupload', fileuploadController::class);
});

/* Route::post('Patient', [PatientController::class, 'store'])
    ->middleware(['auth:sanctum', 'role:doctor', 'can:add patient'])
    ->name('patients.store'); */
/* route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\API\v1', 'middleware' => ['auth:sanctum', 'role:doctor']], function () {
    Route::delete('deletePaymentDetail/{id}', [OperationController::class, 'deletePaymentDetail']);
}); */
