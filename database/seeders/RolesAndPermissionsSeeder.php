<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'MANAGE ALL']);

        Permission::create(['name' => 'ADD PERMISSION']);
        Permission::create(['name' => 'UPDATE PERMISSION']);
        Permission::create(['name' => 'DELETE PERMISSION']);
        Permission::create(['name' => 'LIST PERMISSION']);

        Permission::create(['name' => 'ADD ROLE']);
        Permission::create(['name' => 'UPDATE ROLE']);
        Permission::create(['name' => 'DELETE ROLE']);
        Permission::create(['name' => 'LIST ROLE']);

        Permission::create(['name' => 'ADD EMPLOYEE']);
        Permission::create(['name' => 'UPDATE EMPLOYEE']);
        Permission::create(['name' => 'DELETE EMPLOYEE']);
        Permission::create(['name' => 'LIST EMPLOYEE']);

        Permission::create(['name' => 'ADD INVOICE']);
        Permission::create(['name' => 'UPDATE PENDING INVOICE']);
        Permission::create(['name' => 'UPDATE PROCESSING INVOICE']);
        Permission::create(['name' => 'DELETE INVOICE']);
        Permission::create(['name' => 'LIST INVOICE']);
        Permission::create(['name' => 'PRINT INVOICE']);

        Permission::create(['name' => 'ADD VENDOR']);
        Permission::create(['name' => 'UPDATE VENDOR']);
        Permission::create(['name' => 'DELETE VENDOR']);
        Permission::create(['name' => 'LIST VENDOR']);

        Permission::create(['name' => 'ADD VENDOR BRANCH']);
        Permission::create(['name' => 'UPDATE VENDOR BRANCH']);
        Permission::create(['name' => 'DELETE VENDOR BRANCH']);
        Permission::create(['name' => 'LIST VENDOR BRANCH']);

        $role = Role::create(['name' => 'SUPER ADMIN']);
        $role->givePermissionTo('MANAGE ALL');

        $user = \App\Models\User::create([
            "name" => "Administrator",
            "password" => bcrypt("password"),
            "email" => "admin@gmail.com"
        ]);

        $user->assignRole("SUPER ADMIN");
    }
}
