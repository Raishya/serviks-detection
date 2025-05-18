<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $role = Auth::user()->role;

            if ($role == 'admin') {
                return redirect()->route('admin.home');
            } elseif ($role == 'dokter') {
                return redirect()->route('dokter.home');
            } elseif ($role == 'user') {
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
