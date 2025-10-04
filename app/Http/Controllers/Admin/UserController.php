<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pendingUsers = User::where('is_active', false)->get();
        return view('admin.users.index', compact('pendingUsers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Activar un usuario específico.
     */
    public function activate(User $user)
    {
        if ($user->is_active) {
            return redirect()->route('admin.users.index')->with('info', 'El usuario ya está activo.');
        }
        

        $user->is_active = true;
        $user->save();

        // 2. Opcional: Notificar al usuario (futuro)
        return redirect()->route('admin.users.index')->with('success', 'Usuario ' . $user->email . ' activado correctamente.');
    }
}
