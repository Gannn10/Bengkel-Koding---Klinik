<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\Dokter\JadwalPeriksaController;
use App\Http\Controllers\Dokter\PeriksaPasienController; 
use App\Http\Controllers\Dokter\RiwayatPasienController;
use App\Http\Controllers\Pasien\PoliController as pasienPoliController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. ROUTE UTAMA / DEFAULT
Route::get('/', function () {
    return view('welcome');
});

// 2. ROUTE AUTENTIKASI (LOGIN & REGISTER)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

// 3. ROUTE DASHBOARD BERDASARKAN ROLE

// --- DASHBOARD ADMIN ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('polis', PoliController::class);
    Route::resource('dokters', DokterController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('obat', ObatController::class);
});

// --- DASHBOARD DOKTER ---
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('/dashboard', function () {
        return view('dokter.dashboard');
    })->name('dokter.dashboard');

    Route::resource('jadwal-periksa', JadwalPeriksaController::class);
    
    // --- PERBAIKAN PENTING DI SINI ---
    // 1. Buat route manual untuk create agar bisa menerima {id}
    Route::get('/periksa-pasien/create/{id}', [PeriksaPasienController::class, 'create'])
        ->name('periksa.pasien.create');

    // 2. Route resource sisanya (store, index, dll)
    // Kita gunakan except(['create']) karena create sudah dibuat manual di atas
    Route::resource('periksa-pasien', PeriksaPasienController::class)
        ->names('periksa.pasien')
        ->except(['create']);

    // Route Riwayat Pasien
    Route::get('/riwayat-pasien', [RiwayatPasienController::class, 'index'])->name('dokter.riwayat-pasien.index');
    Route::get('/riwayat-pasien/{id}', [RiwayatPasienController::class, 'show'])->name('dokter.riwayat-pasien.show');
});

// --- DASHBOARD PASIEN ---
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    Route::get('/dashboard', function () {
        return view('pasien.dashboard');
    })->name('pasien.dashboard');

    Route::get('/daftar', [pasienPoliController::class, 'get'])->name('pasien.daftar');
    Route::post('/daftar', [pasienPoliController::class, 'submit'])->name('pasien.daftar.submit');
    Route::get('/get-jadwal/{id_poli}', [pasienPoliController::class, 'getJadwal'])->name('get.jadwal');
});