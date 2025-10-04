<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar que el usuario esté autenticado
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // PASO 1: Verificar que la cuenta esté activa
        if (!$user->is_active) {
            return redirect()->route('activation.pending')
                ->with('error', 'Tu cuenta aún no ha sido activada por un administrador.');
        }

        // PASO 2: Verificar que tenga el rol de Admin
        if (!$user->hasRole('Admin')) {
            abort(403, 'No tienes permisos para acceder a esta área.');
        }

        return $next($request);
    }
}