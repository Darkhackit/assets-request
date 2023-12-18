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
