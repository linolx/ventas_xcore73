<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Resetear caché de roles y permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear Permisos
        $permissions = [
            // Gestión de Usuarios
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'activate-users',
            
            // Gestión de Roles
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            
            // Panel de Administración
            'access-admin-panel',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear Roles
        $adminRole = Role::create(['name' => 'Admin']);
        $userRole = Role::create(['name' => 'User']);

        // Asignar TODOS los permisos al rol Admin
        $adminRole->givePermissionTo(Permission::all());

        // El rol User no tiene permisos especiales por defecto
        // Puedes asignar permisos específicos si lo necesitas

        // Migrar usuarios existentes con is_admin = 1 al rol Admin
        $this->migrateAdminUsers($adminRole, $userRole);

        $this->command->info('Roles y permisos creados exitosamente');
        $this->command->info('Usuarios con is_admin = 1 migrados al rol Admin');
    }

    /**
     * Migrar usuarios existentes al sistema de roles
     */
    private function migrateAdminUsers($adminRole, $userRole): void
    {
        // Asignar rol Admin a usuarios con is_admin = 1
        User::where('is_admin', true)->each(function ($user) use ($adminRole) {
            if (!$user->hasRole('Admin')) {
                $user->assignRole($adminRole);
                $this->command->info("✓ Usuario {$user->name} asignado al rol Admin");
            }
        });

        // Asignar rol User a usuarios sin rol
        User::where('is_admin', false)->each(function ($user) use ($userRole) {
            if (!$user->hasAnyRole(['Admin', 'User'])) {
                $user->assignRole($userRole);
                $this->command->info("✓ Usuario {$user->name} asignado al rol User");
            }
        });
    }
}