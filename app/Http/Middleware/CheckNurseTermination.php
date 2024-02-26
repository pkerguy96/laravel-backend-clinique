<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;

class CheckNurseTermination
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user->role === 'nurse') {
            $terminationDate = $user->termination_date;
            if ($terminationDate && now() > $terminationDate) {

                PersonalAccessToken::where('tokenable_id', $user->id)->delete();
                Auth::logout();
                return redirect('/')->with('error', 'Your access has been terminated.');
            }
        }
        return $next($request);
    }
}
