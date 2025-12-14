<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dosen\DosenController;
use App\Http\Controllers\Dosen\AbsensiController;
use App\Http\Controllers\Mahasiswa\MahasiswaController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DataMasterController;
use App\Http\Controllers\Admin\LaporanController;

Route::get('/', function () {
    return view('welcome');
});

// Rute LOGOUT UNIVERSAL
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

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

Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {

    // Dashboard Mahasiswa (Menampilkan Semua Mata Kuliah)
    Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');

    // Detail Absensi Mata Kuliah (Riwayat Absen)
    Route::get('/matkul/{id_jadwal}', [MahasiswaController::class, 'detailMatkul'])->name('detail.matkul');

    // Rute untuk Absen HARI INI (masih dipertahankan, misalnya di dashboard lama atau link terpisah)
    Route::post('/absen/{id_jadwal}', [MahasiswaController::class, 'absen'])->name('absen');

    // ... rute lainnya
});

Route::prefix('dosen')->name('dosen.')->group(function () {

    // Halaman Utama Dosen
    Route::get('/dashboard', [DosenController::class, 'dashboard'])->name('dashboard');

    // Profil Dosen
    Route::get('/profil', [DosenController::class, 'profil'])->name('profil');

    // Detail Jadwal & Pertemuan
    Route::get('/jadwal/{id_jadwal}', [DosenController::class, 'detailJadwal'])->name('detail.jadwal');

    // KELOLA ABSEN DAN BERITA ACARA PERTEMUAN TERTENTU
    Route::get('/absensi/{id_absen_dosen}/kelola', [DosenController::class, 'kelolaAbsen'])->name('kelola.absen');
    // Aksi UPDATE Berita Acara dan TUTUP Sesi
    Route::put('/absensi/{id_absen_dosen}/update-sesi', [DosenController::class, 'updateSesi'])->name('update.sesi');

    // Aksi UPDATE Status Absensi Mahasiswa Manual
    Route::put('/absensi/{id_absen_dosen}/update-absen', [DosenController::class, 'updateAbsensi'])->name('update.absen');


    // Rute POST untuk aksi Sesi (akan ditambahkan nanti: store/update berita acara)
    Route::post('/jadwal/{id_jadwal}/buka', [DosenController::class, 'bukaSesi'])->name('buka.sesi');
    Route::put('/absensi/{id_absen_dosen}/update', [DosenController::class, 'updateAbsensi'])->name('update.absen');
});



// ... di dalam Route::middleware('auth.admin')->group(function () { ...
// --- RUTE UMUM ADMIN ---
Route::prefix("admin")->name("admin.")->group(function (){
    Route::get('//dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // --- RUTE DATAMASTER ---
    Route::prefix('datamaster')->name('datamaster.')->group(function () {

        // Manajemen Dosen
        Route::prefix('dosen')->name('dosen.')->group(function () {

            Route::get('/', [DataMasterController::class, 'indexDosen'])->name('index');
            Route::get('/create', [DataMasterController::class, 'createDosen'])->name('create');
            Route::post('/', [DataMasterController::class, 'storeDosen'])->name('store');
            Route::delete('/{dosen}', [DataMasterController::class, 'destroyDosen'])->name('destroy');
            Route::get('/{dosen}/edit', [DataMasterController::class, 'editDosen'])->name('edit');
            Route::put('/{dosen}', [DataMasterController::class, 'updateDosen'])->name('update');
        });

        Route::prefix('kelas')->name('kelas.')->group(function () {
            Route::get('/', [DataMasterController::class, 'indexKelas'])->name('index');
            Route::get('/create', [DataMasterController::class, 'createKelas'])->name('create');
            Route::post('/', [DataMasterController::class, 'storeKelas'])->name('store');
            Route::delete('/{kelas}', [DataMasterController::class, 'destroyKelas'])->name('destroy');
            Route::get('/{kelas}/edit', [DataMasterController::class, 'editKelas'])->name('edit');
            Route::put('/{kelas}', [DataMasterController::class, 'updateKelas'])->name('update');
        });

        Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
            Route::get('/', [DataMasterController::class, 'indexMahasiswa'])->name('index');             // Tampilkan semua
            Route::get('/create', [DataMasterController::class, 'createMahasiswa'])->name('create');       // Form tambah
            Route::post('/', [DataMasterController::class, 'storeMahasiswa'])->name('store');             // Simpan data baru
            Route::get('/{mahasiswa}/edit', [DataMasterController::class, 'editMahasiswa'])->name('edit');  // Form edit
            Route::put('/{mahasiswa}', [DataMasterController::class, 'updateMahasiswa'])->name('update');    // Simpan perubahan
            Route::delete('/{mahasiswa}', [DataMasterController::class, 'destroyMahasiswa'])->name('destroy'); // Hapus
        });

        Route::prefix('matkul')->name('matkul.')->group(function () {
            Route::get('/', [DataMasterController::class, 'indexMatkul'])->name('index');
            Route::get('/create', [DataMasterController::class, 'createMatkul'])->name('create');
            Route::post('/', [DataMasterController::class, 'storeMatkul'])->name('store');
            Route::delete('/{matkul}', [DataMasterController::class, 'destroyMatkul'])->name('destroy');
            Route::get('/{matkul}/edit', [DataMasterController::class, 'editMatkul'])->name('edit');
            Route::put('/{matkul}', [DataMasterController::class, 'updateMatkul'])->name('update');
        });

        Route::prefix('jadwal')->name('jadwal.')->group(function () {
        Route::get('/', [DataMasterController::class, 'indexJadwal'])->name('index');
        Route::get('/create', [DataMasterController::class, 'createJadwal'])->name('create');
        Route::post('/', [DataMasterController::class, 'storeJadwal'])->name('store');
        Route::delete('/{jadwal}', [DataMasterController::class, 'destroyJadwal'])->name('destroy');
        });
    });

    // --- RUTE LAPORAN ---
    Route::prefix('laporan')->name('laporan.')->group(function () {
        // Form Pemilihan
        Route::get('/rekap-absensi', [LaporanController::class, 'showFormRekap'])->name('rekap.form');
        // Generasi Laporan
        Route::post('/rekap-absensi', [LaporanController::class, 'generateRekap'])->name('rekap.generate');
    });


});
