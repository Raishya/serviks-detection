<?php

// app/Http/Controllers/Auth/LoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return response()->view('welcome')
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');
    }

    public function login(Request $request): RedirectResponse
    {
        $input = $request->all();
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $input['email'], 'password' => $input['password']])) {
            Log::info('User logged in: ', ['user_id' => Auth::user()->id, 'type' => Auth::user()->type]);

            return $this->redirectToUserHome();
        } else {
            return $this->sendFailedLoginResponse($request);
        }
    }

    protected function sendFailedLoginResponse(Request $request): RedirectResponse
    {
        return redirect()->route('login')
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => [trans('Email atau password yang Anda masukkan salah.')],
            ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
    private function redirectToUserHome(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->type == 'dokter') {
            return redirect()->route('dokter.home');
        } elseif ($user->type == 'admin') {
            return redirect()->route('admin.home');
        } else {
            return redirect()->route('home');
        }
    }
}