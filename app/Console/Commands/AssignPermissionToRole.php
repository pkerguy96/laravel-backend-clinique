<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class AssignPermissionToRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */   protected $signature = 'assign-permission-to-role';



    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a permission to a role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = Auth::user();

        if (Auth::check()) {
            $user = Auth::user();

            $user->assignRole('doctor');
            $this->info('tajouta');
        } else {
            // Handle the case when the user is not authenticated
            // e.g., redirect to login page or return an error response
            $this->info('nope');
        }
        /*    $role = Role::firstOrCreate(['name' => 'doctor']);

        // Find the permission
        $permission = Permission::where('name', 'add patient')->first();

        if ($permission) {
            // Assign the permission to the role
            $role->givePermissionTo($permission);
            $this->info('Permission assigned to role successfully.');
        } else {
            $this->error('Permission not found. Make sure it is created.');
        } */
    }
}
