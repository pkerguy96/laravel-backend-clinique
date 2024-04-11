<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;


trait PermissionCheckTrait
{

    public function checkPermission($permission)
    {
        $user = Auth::user();
        setPermissionsTeamId($user);


        if (!Auth::user()->can($permission)) {
            return response()->json(['error' => 'Unauthorized action.'], Response::HTTP_FORBIDDEN);
        }
    }
}
