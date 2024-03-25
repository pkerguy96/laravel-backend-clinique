<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TeamsPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!empty(auth()->user())) {
            $teamId = Session::get('team_id');
            if ($teamId !== null && Auth::user()->role === 'doctor') {
                setPermissionsTeamId(session(Auth::user()->id));
            }
        }
        return $next($request);
    }
}
