<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Session\Session;

class RolesController extends Controller
{
    use HttpResponses;
    public function createRole()
    {
    }
    public function grantAccess(Request $request)
    {
        try {
            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
            /*  $user = Auth::user(); */
           /*  $currentGuard = Auth::getDefaultDriver();

            dd($currentGuard); */
            

            /* $user = Auth::guard('api')->user();
            dd($user); */
            $user = User::where('id', 2)->first();

            
           Role::create(['name' => 'benj',  'team_id' => 2]);
         setPermissionsTeamId($user);
            $user->assignRole('benj');

            /*    $teamId = session('team_id');

            dd($teamId); */
           // $role = Role::where('name', 'sexer')->where('guard_name', 'api')->first();

            //$user->assignRole('sexer');

            /*             $role = Role::findByName('sexer', 'web'); */
            /* $role = Role::create(['name' => 'haway', 'guard_name' => 'web', 'team_id' => 1]); */
            /* 
            dd(Role::where('name', 'haway')->first());
            $user->assignRole($hawayRole, 1); *//* 
            $user->assignRole([$role, 'team_id' =>  1]); */
            /*   Role::create(['name' => 'reader', 'guard_name' => 'web', 'team_id' => $user->id]);
            Role::create(['name' => 'reader', 'guard_name' => 'web', 'team_id' => 2]);
            Role::create(['name' => 'reader', 'guard_name' => 'web', 'team_id' => 3]); */
            /*    $roles = $user->roles;
  
            $permissions = $user->can('');
            dd($permissions);
            if ($user->role === 'nurse') {
                return $this->error(null, 'Only doctors can give permissions and assign roles.', 400);
            } */
            /*   $nurse = User::where('id', $request->nurse)->where('role', 'nurse')->where('doctor_id', $user->id)->first();
            if (!$nurse) {
                return $this->error(null, 'No nurse found', 400);
            } */
            /*  $roles = $user->roles;
            dd($roles); */
            //  $role = Role::create(['name' => 'jonny', 'guard_name' => 'web']);
            /*  $role =  Role::create(['name' => 'sexer', 'guard_name' => 'web', 'team_id' => 1]);
            $permissions = $request->permissions;

            foreach ($permissions as $permission) {
                $permission = Permission::create(['name' => $permission, 'guard_name' => 'web', 'team_id' => 1]);
                $role->givePermissionTo($permission);
            } */
        } catch (\Throwable $th) {
            return $this->error(null, $th, 500);
        }
    }
}
