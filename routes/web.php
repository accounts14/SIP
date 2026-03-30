<?php

use App\Http\Controllers\Web\WebAuthController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Api\SchoolController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// ==========================================
// AUTH ROUTES
// ==========================================
Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
Route::post('/login/{role}', [AuthController::class, 'login']);

Route::get('/register', [WebAuthController::class, 'showRegister'])->name('register');

// ==========================================
// DASHBOARD ROUTES
// ==========================================
Route::get('/dashboard', [WebAuthController::class, 'showDashboard'])->name('dashboard.admin');
Route::get('/pimpinan', [WebAuthController::class, 'showPimpinanDashboard'])->name('dashboard.pimpinan');

// ==========================================
// PUBLIC ROUTES (Akses Tanpa Login)
// ==========================================

// 1. Route untuk menampilkan form pendaftaran siswa
Route::view('/daftar-siswa', 'auth.daftar-siswa')->name('daftar.siswa');
// Catatan: Proses submit form (POST) ditangani oleh API Route ('/api/student') 
// yang sudah terdaftar di routes/api.php via resource.

// 2. Route pendaftaran siswa DENGAN slug sekolah di URL  ← BARU
//    Contoh: /daftar-siswa/sman-7-bandar-lampung
Route::get('/daftar-siswa/{schoolSlug}', function (string $schoolSlug) {
    return view('auth.daftar-siswa', ['schoolSlug' => $schoolSlug]);
})->where('schoolSlug', '[a-z0-9-]+')->name('daftar.siswa.sekolah');

// ==========================================
// SCHOOL ROUTES (Public Info)
// ==========================================
Route::prefix('schools')->group(function() {
    Route::get('/', [SchoolController::class, 'index']);
    Route::get('/{identifier}', [SchoolController::class, 'show'])
        ->where('identifier', '[0-9]+|[a-z0-9-]+');
});
// ==========================================
// SCHOOL PORTAL LOGIN
// ==========================================
Route::view('/login-sekolah', 'auth.login-sekolah')->name('login.sekolah');
Route::view('/school-dashboard', 'dashboard.sekolah-admin')->name('dashboard.school');
Route::view('/pimpinan-sekolah', 'dashboard.pimpinan-sekolah')->name('dashboard.pimpinan.sekolah');
// ==========================================
// SISWA (MEMBER) PORTAL
// ==========================================
Route::view('/login-siswa', 'auth.login-siswa')->name('login.siswa');
Route::view('/siswa/dashboard', 'dashboard.dashboard-siswa')->name('dashboard.siswa');