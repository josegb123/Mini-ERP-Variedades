<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'create-users']);
        Permission::create(['name' => 'edit-users']);
        Permission::create(['name' => 'delete-users']);

        Permission::create(['name' => 'make-orders']);
        Permission::create(['name' => 'view-debts']);
        Permission::create(['name' => 'manage-financial']);

        $adminRole = Role::create(['name' => 'Administrador']);
        $sellerRole = Role::create(['name' => 'Vendedor']);

        $adminRole->givePermissionTo([
            'create-users',
            'edit-users',
            'delete-users',
            'make-orders',
            'view-debts',
            'manage-financial',
        ]);

        $sellerRole->givePermissionTo([
            'make-orders',
            'view-debts',
        ]);
    }
}
