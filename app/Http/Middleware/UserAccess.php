<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $userType): Response
    {
        if (Auth::check() && Auth::user()->type == $userType) {
            return $next($request);
        }

        Log::warning('Unauthorized access attempt by user', [
            'user_id' => Auth::check() ? Auth::user()->id : null,
            'user_type' => Auth::check() ? Auth::user()->type : null,
            'required_type' => $userType,
            'route' => $request->route()->getName()
        ]);

        return response()->json(['You do not have permission to access this page.'], 403);
    }
}
