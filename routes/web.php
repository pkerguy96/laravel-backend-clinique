<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\API\v1\fileuploadController;
use App\Http\Controllers\API\v1\PasswordResetController;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Middleware\VerifyCsrfToken;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Route::get('/file-upload/{id}', [fileuploadController::class, 'show']);
Route::post('/api/v1/resetlink', [PasswordResetController::class, 'sendResetLinkEmail'])->withoutMiddleware(VerifyCsrfToken::class);


Route::get('/setup', function () {
    $credentials = [
        'email' => 'admin@admin.com',
        'password' => 'password',
    ];
    if (!Auth::attempt($credentials)) {
        $user = new User();
        $user->nom = 'Admin';
        $user->prenom = 'Admin';
        $user->cin = 'Admin';
        $user->date = '2023-12-31';
        $user->address = 'Admin';
        $user->sex = 'male';
        $user->role = 'doctor';
        $user->phone_number = '0606060606';
        $user->email = $credentials['email'];
        $user->password = Hash::make($credentials['password']);
        $user->save();
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $adminToken = $user->createToken('admin-token');
            $basicToken = $user->createToken('basic-token', ['create', 'update']);
            return [
                'admin' => $adminToken->plainTextToken,
                'basic' => $basicToken->plainTextToken,
            ];
        }
    }
});
