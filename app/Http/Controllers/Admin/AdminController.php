<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\JadwalMengajar;
use App\Models\AbsenMahasiswa;
use App\Models\MatkulDasar;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Menampilkan Dashboard Admin dengan statistik ringkas.
     */
    public function dashboard()
    {
        // Menampilkan Statistik Data Master
        $totalMahasiswa = Mahasiswa::count();
        $totalDosen = Dosen::count();
        $totalKelas = Kelas::count();
        $totalMatkul = MatkulDasar::count();
        $totalJadwal = JadwalMengajar::count();

        return view('admin.dashboard', compact(
            'totalMahasiswa',
            'totalDosen',
            'totalKelas',
            'totalJadwal',
            'totalMatkul'
        ));
    }

    // Nanti bisa ditambahkan fungsi untuk menghasilkan laporan PDF/Excel di sini
}
