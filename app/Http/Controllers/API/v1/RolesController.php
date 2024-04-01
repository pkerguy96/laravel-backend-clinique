<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\RoleResource;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Session\Session;

class RolesController extends Controller
{
    use HttpResponses;
    public function createRole()
    {
    }
    public function getRoles()
    {
        try {
            $authenticatedUser = auth()->user();
            if ($authenticatedUser->role === 'nurse') {
                return $this->error(null, 'Only doctors are allowed access!', 401);
            }
            $roles = RoleResource::collection(Role::where('team_id', $authenticatedUser->id)->get());
            return $this->success($roles, 'success', 201);
        } catch (\Throwable $th) {
            $this->error($th, 'error', 501);
        }
    }
    public function grantAccess(Request $request)
    {
        try {
            $user = auth()->user();
            if ($user->role === 'nurse') {
                return $this->error(null, 'Only doctors are allowed access!', 501);
            }
            setPermissionsTeamId($user);
            $nurse = User::where('doctor_id', $user->id)->where('id', $request->nurseid)->first();

            if (!$nurse) {
                return $this->error(null, 'there is no nurse!', 501);
            }
            $role = Role::findByName($request->rolename);
            if (!$role) {
                throw RoleDoesNotExist::named($request->rolename, 'sanctum');
            }
            $role->syncPermissions([]);
            $permissions = $request->permissions;
            $role->syncPermissions($permissions);

            $roles = $nurse->roles;
            foreach ($roles as $singlerole) {
                $nurse->removeRole($singlerole);
            }
            $nurse->assignRole($request->rolename);

            return $this->success(null, 'permissions updated for the nurse', 201);
        } catch (RoleDoesNotExist $exception) {

            return $this->error(null, $exception->getMessage(), 500);
        } catch (\Throwable $th) {
            return $this->error(null, $th->getMessage(), 500);
        }
    }
    /*   foreach ($permissions as $permission) {
                $permission = Permission::create(['name' => $permission, 'team_id' => 2]);
            }
            $role->syncPermissions($permissions); */
}
  /*             $roles = $nurse->getPermissionsViaRoles(); */