<?php

// app/Helpers/helpers.php

use Illuminate\Support\Facades\Auth;

if (!function_exists('getHomeRoute')) {
    function getHomeRoute()
    {
        $user = Auth::user();
        if ($user) {
            error_log('User role: ' . $user->role);  // Debugging line
            switch ($user->role) {
                case 'admin':
                    return route('admin.home');
                case 'dokter':
                    return route('dokter.home');
                case 'user':
                    return route('home');
                default:
                    return route('home');
            }
        }
        return route('home');
    }
}
