<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Jika belum login, arahkan ke halaman login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Jika role pengguna tidak sesuai dengan role yang diizinkan
        if (Auth::user()->role !== $role) {
            return redirect()->route('home')
                ->with('error', 'Akses ditolak! Halaman tersebut hanya dapat diakses oleh ' . ucfirst($role) . '.');
        }

        return $next($request);
    }
}
