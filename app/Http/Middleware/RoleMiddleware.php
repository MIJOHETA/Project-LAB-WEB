<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // 2. Cek apakah role user ada di dalam daftar role yang diizinkan
        // Contoh: apakah 'dokter' ada di dalam ['admin', 'dokter']?
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 3. Jika tidak cocok, tampilkan 403 Forbidden (Bukan 404)
        // Ini membantu membedakan antara "Halaman Hilang" vs "Salah Role"
        abort(403, 'Akses Ditolak. Role Anda (' . $user->role . ') tidak memiliki izin.');
    }
}