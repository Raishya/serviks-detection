<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\DiagnosaController;
use App\Http\Controllers\ArsipController;
use App\Http\Middleware\CheckUserAccess;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;


Route::middleware([RedirectIfAuthenticated::class, PreventBackHistory::class])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});



Route::middleware(PreventBackHistory::class)->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/', function () {
        return response()->view('welcome')
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');
    })->name('welcome');

    Route::middleware(['auth', CheckUserAccess::class])->group(function () {
        Route::get('/rekam-medis/{user_id}', [RekamMedisController::class, 'rekamMedis'])->name('rekam-medis');
    });

    Route::middleware(['auth', 'user-access:user'])->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('home');
    });

    Route::get('/tfjs', [HomeController::class, 'tfjs'])->name('tfjs');

    Route::middleware(['auth', 'user-access:dokter'])->group(function () {
        Route::get('/dokter/home', [HomeController::class, 'dokterHome'])->name('dokter.home');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('pasien/addPasien', [PasienController::class, 'create'])->name('addPasien');
        Route::post('pasien/savePasien', [PasienController::class, 'store'])->name('savePasien');
        Route::get('pasien/diagnosa', [DiagnosaController::class, 'index'])->name('diagnosa');
        Route::post('pasien/predict', [DiagnosaController::class, 'predict'])->name('diagnosa.predict');
        Route::post('pasien/saveAll', [DiagnosaController::class, 'save'])->name('diagnosa.save');
        Route::post('/save-canvas-images', [DiagnosaController::class, 'saveCanvasImages']);
        Route::post('/save-image-path', [DiagnosaController::class, 'saveImage'])->name('save.image.path');
        Route::post('/kolposkop/save-image', [DiagnosaController::class, 'saveImageFromCamera'])->name('kolposkop.saveImage');
        Route::get('/arsip', [DiagnosaController::class, 'arsip'])->name('diagnosa.arsip');
        Route::get('/arsip/search', [ArsipController::class, 'search'])->name('diagnosa.search');
        Route::get('/kolposkop', [DiagnosaController::class, 'kolposkop'])->name('kolposkop');

        Route::resource('diagnosas', ArsipController::class);
    });

    Route::middleware(['auth', 'user-access:admin'])->group(function () {
        Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');
    });
});
