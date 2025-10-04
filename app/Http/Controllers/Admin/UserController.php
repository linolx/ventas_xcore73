<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Mostrar lista de usuarios pendientes de activación
     */
    public function index()
    {
        // Obtener usuarios que no están activos (is_active = 0)
        $pendingUsers = User::where('is_active', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.users.index', compact('pendingUsers'));
    }

    /**
     * Mostrar todos los usuarios (activos e inactivos)
     */
    public function all()
    {
        $users = User::with('roles')->orderBy('created_at', 'desc')->get();
        return view('admin.users.all', compact('users'));
    }

    /**
     * Mostrar formulario de edición de usuario
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'is_active' => 'boolean',
            'roles' => 'array',
            'roles.*' => 'exists:roles,name'
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_active' => $request->has('is_active')
        ]);

        // Sincronizar roles
        if (isset($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }

        return redirect()->route('admin.users.all')
            ->with('success', "Usuario '{$user->name}' actualizado exitosamente.");
    }

    /**
     * Activar una cuenta de usuario
     */
    public function activate(User $user)
    {
        // Verificar que el usuario no esté ya activo
        if ($user->is_active) {
            return redirect()->route('admin.users.index')
                ->with('info', 'Este usuario ya está activo.');
        }

        // Activar el usuario
        $user->is_active = true;
        $user->save();

        // Asignar rol 'User' si no tiene ningún rol
        if (!$user->hasAnyRole(['Admin', 'User'])) {
            $user->assignRole('User');
        }

        return redirect()->route('admin.users.index')
            ->with('success', "La cuenta de {$user->name} ha sido activada exitosamente.");
    }
}