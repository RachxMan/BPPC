<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class StatusMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->status !== 'Aktif') {
            Auth::logout();
            return redirect('/login')->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.');
        }

        return $next($request);
    }
}
