<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use App\Mail\PasswordReset;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|em
        ail']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'there are no user'], 404);
        }

        $token = Str::random(20);

        $row = DB::table('password_reset_tokens')->where('email', $user->email)->first();

        if (!$row) {
            DB::table('password_reset_tokens')->insert([
                'email' => $user->email,
                'token' => $token,
            ]);
        } else {
            DB::table('password_reset_tokens')->where('email', $user->email)->update([
                'token' => $token,
            ]);
        }

        try {
            $mail = new PasswordReset(['to' => [$user->email], 'token' => $token]);
            Mail::send($mail);
            return  response()->json(['message' => 'Reset link sent to your email'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unable to send reset link '], 400);
        }
    }
    /*  public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password has been reset successfully'], 200)
            : response()->json(['message' => 'Unable to reset password'], 400);
    } */
}
