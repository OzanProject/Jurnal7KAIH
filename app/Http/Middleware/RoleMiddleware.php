<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Check if user role is in the allowed roles array
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Redirect based on role if unauthorized for this specific route
        return match ($user->role) {
            'super_admin' => redirect()->route('dashboard.super_admin'),
            'admin'       => redirect()->route('dashboard.admin'),
            'guru'        => redirect()->route('dashboard.guru'),
            'siswa'       => redirect()->route('dashboard.siswa'),
            'orang_tua'   => redirect()->route('dashboard.orang_tua'),
            default       => redirect('/'), // Fallback
        };
    }
}
