<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Mostrar lista de roles
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('-', $permission->name)[1] ?? 'otros';
        });
        
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Guardar nuevo rol
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name'
        ], [
            'name.required' => 'El nombre del rol es obligatorio',
            'name.unique' => 'Este rol ya existe'
        ]);

        $role = Role::create(['name' => $validated['name']]);
        
        if (!empty($validated['permissions'])) {
            $role->givePermissionTo($validated['permissions']);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', "Rol '{$role->name}' creado exitosamente.");
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Role $role)
    {
        // Prevenir edición de roles protegidos
        if (in_array($role->name, ['Admin', 'User'])) {
            return redirect()->route('admin.roles.index')
                ->with('warning', "El rol '{$role->name}' es un rol del sistema y solo se pueden editar sus permisos.");
        }

        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('-', $permission->name)[1] ?? 'otros';
        });
        
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Actualizar rol
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        // Prevenir renombrar roles protegidos
        if (in_array($role->name, ['Admin', 'User']) && $role->name !== $validated['name']) {
            return back()->with('error', "No puedes renombrar el rol '{$role->name}'.");
        }

        $role->update(['name' => $validated['name']]);
        
        // Sincronizar permisos
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', "Rol '{$role->name}' actualizado exitosamente.");
    }

    /**
     * Eliminar rol
     */
    public function destroy(Role $role)
    {
        // Prevenir eliminación de roles protegidos
        if (in_array($role->name, ['Admin', 'User'])) {
            return back()->with('error', "No puedes eliminar el rol '{$role->name}'.");
        }

        $roleName = $role->name;
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', "Rol '{$roleName}' eliminado exitosamente.");
    }

    /**
     * Mostrar permisos de un rol
     */
    public function permissions(Role $role)
    {
        $allPermissions = Permission::all()->groupBy(function($permission) {
            return explode('-', $permission->name)[1] ?? 'otros';
        });
        
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.permissions', compact('role', 'allPermissions', 'rolePermissions'));
    }

    /**
     * Actualizar permisos de un rol
     */
    public function updatePermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', "Permisos del rol '{$role->name}' actualizados exitosamente.");
    }
}