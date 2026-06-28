<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: middleware('role:admin') or middleware('role:petugas')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            // Redirect to the correct dashboard based on actual role
            if ($request->user()) {
                $redirect = $request->user()->role === 'admin'
                    ? route('admin.dashboard')
                    : route('petugas.dashboard');

                return redirect($redirect)
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
