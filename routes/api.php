<?php

use App\Http\Controllers\API\v1\AdminController;
use App\Http\Controllers\API\v1\AppointmentController;
use App\Http\Controllers\API\v1\AuthController;
use App\Http\Controllers\API\v1\fileuploadController;
use App\Http\Controllers\API\v1\NurseController;
use App\Http\Controllers\API\v1\OperationController;
use App\Http\Controllers\API\v1\OrdonanceController;
use App\Http\Controllers\API\v1\PatientController;
use App\Http\Controllers\API\v1\StockController;
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
route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\API\v1', 'middleware' => 'auth:sanctum'], function () {
    Route::get('Admin/profile', [AdminController::class, 'getpicture']);
    Route::get('Admin/logout', [AuthController::class, 'Logout']);
    Route::post('Admin/store/profile', [AdminController::class, 'storeprofile']);
    Route::post('Admin/update/profile', [AdminController::class, 'ModifyProfile']);
    Route::get('patientDetails/{id}', [PatientController::class, 'patientDetails']);
    Route::get('getByOperationId/{id}', [OperationController::class, 'getByOperationId']);
    Route::get('uploadsInfo', [fileuploadController::class, 'uploadsInfo']);

    Route::delete('deletePaymentDetail/{id}', [OperationController::class, 'deletePaymentDetail']);






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
