<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckUserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Periksa apakah user adalah pasien dan mengakses rekam medis mereka sendiri
        if ($user->type === 'user' && $request->route('user_id') != $user->id) {
            Log::warning('Unauthorized access attempt', ['user_id' => $user->id, 'attempted_user_id' => $request->route('user_id')]);
            return redirect('/home')->with('error', 'Anda tidak memiliki akses ke rekam medis ini.');
        }

        return $next($request);
    }
}
