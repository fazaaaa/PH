<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            // Jika pengguna tidak terotentikasi, arahkan ke halaman login
            return redirect('login');
        }
    
        $user = Auth::user();
    
        // Periksa apakah pengguna memiliki salah satu peran yang dibutuhkan
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }
    
        // Jika pengguna tidak memiliki peran yang dibutuhkan, berikan respons akses ditolak
        return response('Permission Denied', 403);
    }
}
