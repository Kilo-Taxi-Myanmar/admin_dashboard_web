<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'read user']);
        Permission::create(['name' => 'update user']);
        Permission::create(['name' => 'delete user']);

        Permission::create(['name' => 'create fee']);
        Permission::create(['name' => 'read fee']);
        Permission::create(['name' => 'update fee']);
        Permission::create(['name' => 'delete fee']);

        Permission::create(['name' => 'create transaction']);
        Permission::create(['name' => 'read transaction']);
        Permission::create(['name' => 'update transaction']);
        Permission::create(['name' => 'delete transaction']);

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $staffRole = Role::create(['name' => 'staff']);
        $userRole = Role::create(['name' => 'user']);
        $userRole = Role::create(['name' => 'customer']);


        // Assign permissions to roles
        $adminRole->givePermissionTo([
            'create user', 'read user', 'update user', 'delete user',
            'create fee', 'read fee', 'update fee', 'delete fee',
            'create transaction', 'read transaction', 'update transaction', 'delete transaction',
        ]);

        $staffRole->givePermissionTo([
            'create user', 'read user', 'update user',
            'create fee', 'read fee', 'update fee',
            'create transaction', 'read transaction', 'update transaction',
        ]);
    }
}
