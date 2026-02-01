<?php
namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Enums\PermissionEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear todos los permisos definidos en el Enum
        foreach (PermissionEnum::cases() as $permission) {
            Permission::findOrCreate($permission->value);
        }

        // Configurar Rol ADMIN (Todo)
        Role::findOrCreate(RoleEnum::ADMIN->value)
            ->syncPermissions(PermissionEnum::cases());

        // Configurar Rol VENDEDOR (Productos + Ver Usuarios)
        Role::findOrCreate(RoleEnum::VENDEDOR->value)
            ->syncPermissions([
                PermissionEnum::PRODUCT_CREATE,
                PermissionEnum::PRODUCT_READ,
                PermissionEnum::PRODUCT_UPDATE,
                PermissionEnum::PRODUCT_DELETE,
                PermissionEnum::USER_READ,
            ]);

        // Configurar Rol ESTANDAR (Solo Lectura)
        Role::findOrCreate(RoleEnum::ESTANDAR->value)
            ->syncPermissions([
                PermissionEnum::USER_READ,
                PermissionEnum::PRODUCT_READ,
            ]);
    }
}