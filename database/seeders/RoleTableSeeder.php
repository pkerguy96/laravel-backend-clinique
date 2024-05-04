<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::create(['name' => 'access_patient', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'insert_patient', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'update_patient', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'delete_patient', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'detail_patient', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'access_ordonance', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'insert_ordonance', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'update_ordonance', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'delete_ordonance', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'access_creance', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'search_creance', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'access_debt', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'insert_debt', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'delete_debt', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'access_document', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'insert_document', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'delete_document', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'download_document', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'detail_document', 'guard_name' => 'sanctum']);
    }
}
