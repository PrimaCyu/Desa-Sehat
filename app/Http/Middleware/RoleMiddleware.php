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
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();

        if ($role === 'kader' && !$user->isKader()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Kader.');
        }

        if ($role === 'warga' && !$user->isWarga()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Warga.');
        }

        return $next($request);
    }
}
