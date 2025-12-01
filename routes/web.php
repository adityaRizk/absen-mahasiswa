<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dosen\DosenController;
use App\Http\Controllers\Dosen\AbsensiController;
use App\Http\Controllers\Mahasiswa\MahasiswaController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DataMasterController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/dashboard', [DosenController::class, 'dashboard'])->name('dosen.dashboard');
Route::get('/jadwal', [DosenController::class, 'jadwalMengajar'])->name('dosen.jadwal');

// Absensi Controller
Route::prefix('absensi')->name('dosen.absensi.')->group(function () {
    // Membuka Sesi Absensi
    Route::post('/buka/{id_jadwal}', [AbsensiController::class, 'bukaSesiAbsensi'])->name('buka');

    // Menutup Sesi Absensi
    Route::post('/tutup/{id_jadwal}', [AbsensiController::class, 'tutupSesiAbsensi'])->name('tutup');

    // Menampilkan Sesi Absen Aktif
    Route::get('/sesi-aktif/{id_jadwal}', [AbsensiController::class, 'sesiAktif'])->name('sesi-aktif');
});

// Dashboard Mahasiswa
Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('mahasiswa.dashboard');

// Rute Absensi Mahasiswa
Route::post('/absen/{id_jadwal}', [MahasiswaController::class, 'absen'])->name('mahasiswa.absen');

// ... di dalam Route::middleware('auth.admin')->group(function () { ...

// --- RUTE UMUM ADMIN ---
Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// --- RUTE DATA MASTER ---
Route::prefix('datamaster')->name('admin.datamaster.')->group(function () {

    // Manajemen Dosen
    Route::prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/', [DataMasterController::class, 'indexDosen'])->name('index');
        Route::get('/create', [DataMasterController::class, 'createDosen'])->name('create');
        Route::post('/', [DataMasterController::class, 'storeDosen'])->name('store');
        Route::delete('/{dosen}', [DataMasterController::class, 'destroyDosen'])->name('destroy');
        // Tambahkan rute untuk edit/update di sini
    });

    // Manajemen Mahasiswa (Rute serupa dengan Dosen)
    // ...

    // Manajemen Kelas (Rute serupa dengan Dosen)
    // ...

    // Manajemen Mata Kuliah (Rute serupa dengan Dosen)
    // ...
});




