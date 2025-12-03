<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek apakah pengguna sudah login
        if (!Auth::check()) {
            // Jika tidak login, kirim ke halaman login
            return redirect('login');
        }

        // 2. Cek apakah peran pengguna sesuai dengan peran yang diminta
        // $role adalah parameter yang dilewatkan dari rute (misalnya: 'admin')
        if (Auth::user()->role !== $role) {
            // Jika peran tidak sesuai, kembalikan response 403 Forbidden
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin yang diperlukan.');
        }
        if (! in_array($role, ['admin', 'manager'])) {
            // Jika peran tidak sesuai, kembalikan response 403 Forbidden
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin yang diperlukan.');
        }

        // 3. Jika peran sesuai, lanjutkan permintaan
        return $next($request);
    }
}