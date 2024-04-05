<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\RoleCollection;
use App\Http\Resources\V1\RoleResource;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Session\Session;
use Illuminate\Support\Facades\Artisan;

class RolesController extends Controller
{
    use HttpResponses;
    public function getUsersViaRoles()
    {
        try {
            $user = Auth::user();
            if ($user->role === 'nurse') {
                return $this->error(null, 'Seuls les médecins sont autorisés à accéder.', 401);
            }
            $roles = Role::where('team_id', $user->id)->with('users')->latest('created_at')->get();
            return new RoleCollection($roles);
        } catch (\Throwable $th) {
            $this->error($th->getMessage(), 'error', 501);
        }
    }
    public function createRole(Request $request)
    {
        try {
            $user = auth()->user();
            if ($user->role === 'nurse') {
                return $this->error(null, 'Seuls les médecins sont autorisés à accéder.', 401);
            }
            /*  Artisan::call('cache:forget', ['key' => 'spatie.permission.cache']); */
            setPermissionsTeamId($user);
            /*   $user->unsetRelation('roles')->unsetRelation('permissions'); */

            $existingRole = Role::where('name', $request->rolename)->where('team_id', $user->id)->first();

            if ($existingRole) {
                return $this->error(null, 'Le rôle existe déjà', 409);
            }
            Role::create(['name' => $request->rolename, 'team_id' => $user->id]);
            return $this->success(null, "Le rôle a été ajouté.", 201);
        } catch (RoleDoesNotExist $exception) {
            return $this->error(null, $exception->getMessage(), 500);
        } catch (\Throwable $th) {
            $this->error($th->getMessage(), 'error', 501);
        }
    }
    public function getRoles()
    {
        try {
            $authenticatedUser = auth()->user();
            if ($authenticatedUser->role === 'nurse') {
                return $this->error(null, 'Seuls les médecins sont autorisés à accéder.', 401);
            }
            $roles = RoleResource::collection(Role::where('team_id', $authenticatedUser->id)->get());
            return $this->success($roles, 'success', 201);
        } catch (\Throwable $th) {
            $this->error($th->getMessage(), 'error', 501);
        }
    }
    public function grantAccess(Request $request)
    {
        try {
            $user = auth()->user();
            if ($user->role === 'nurse') {
                return $this->error(null, 'Seuls les médecins sont autorisés à accéder.', 501);
            }
            setPermissionsTeamId($user);
            $nurse = User::where('doctor_id', $user->id)->where('id', $request->nurseid)->first();

            if (!$nurse) {
                return $this->error(null, "Aucune infirmière n'a été trouvée", 501);
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

            return $this->success(null, "L'autorisation a été mise à jour avec succès.", 201);
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
    /*             $roles = $nurse->getPermissionsViaRoles(); */
    public function userPermissions(Request $request)
    {
        try {
            $user = auth()->user();
            if ($user->role === 'nurse') {
                return $this->error(null, 'Seuls les médecins sont autorisés à accéder.', 501);
            }
            setPermissionsTeamId($user);
            //TODO: potential bug


            $role = Role::findByName($request->rolename);
            return $this->success($role, 'sss', 200);


            if (!$role) {
                throw RoleDoesNotExist::named($request->rolename, 'sanctum');
            }
            $permissions = $role->permissions->pluck('name')->toArray();
            return $this->success($permissions, 'success', 201);
        } catch (RoleDoesNotExist $exception) {

            return $this->error(null, $exception->getMessage(), 500);
        } catch (\Throwable $th) {
            return $this->error(null, $th->getMessage(), 500);
        }
    }
    public function deleteRole($id)
    {
        try {
            $user = auth()->user();
            if ($user->role === 'nurse') {
                return $this->error(null, 'Seuls les médecins sont autorisés à accéder.', 501);
            }
            setPermissionsTeamId($user);
            $role = Role::where('id', $id)->where('team_id', $user->id)->first();

            $role->delete();
            return $this->success(null, 'deleted success', 201);
        } catch (\Throwable $th) {
            return $this->error(null, $th->getMessage(), 500);
        }
    }
}
