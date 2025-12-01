<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\JadwalMengajar;
use App\Models\AbsenMahasiswa;
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
        $totalJadwal = JadwalMengajar::count();

        // Contoh Laporan Sederhana: Kehadiran Tertinggi (Top 5)
        // Kita hitung jumlah kehadiran 'Hadir' per mahasiswa
        $topKehadiran = AbsenMahasiswa::select('nim', DB::raw('count(*) as total_hadir'))
            ->where('status', 'Hadir')
            ->groupBy('nim')
            ->orderByDesc('total_hadir')
            ->limit(5)
            ->with('mahasiswa') // Mengambil data mahasiswa terkait
            ->get();

        return view('admin.dashboard', compact(
            'totalMahasiswa',
            'totalDosen',
            'totalKelas',
            'totalJadwal',
            'topKehadiran'
        ));
    }

    // Nanti bisa ditambahkan fungsi untuk menghasilkan laporan PDF/Excel di sini
}
