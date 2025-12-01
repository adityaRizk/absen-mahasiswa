<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Dosen;
use App\Models\JadwalMengajar;

class DosenController extends Controller
{
    /**
     * Menampilkan Dashboard Dosen dengan jadwal mengajar hari ini.
     */
    public function dashboard()
    {
        // Mendapatkan objek Dosen yang sedang login
        $dosen = Auth::guard('dosen')->user();

        // Mendapatkan NIP dosen
        $nip = $dosen->nip;

        // Mendapatkan nama hari ini dalam Bahasa Inggris (misal: 'Monday')
        $hari_ini = now()->format('l');

        // Mendapatkan semua jadwal mengajar dosen tersebut untuk hari ini
        $jadwalHariIni = JadwalMengajar::where('nip', $nip)
                                    ->where('hari', $hari_ini)
                                    // Load relasi Mata Kuliah dan Kelas
                                    ->with(['matkul', 'kelas'])
                                    ->get();

        return view('dosen.dashboard', compact('dosen', 'jadwalHariIni'));
    }

    /**
     * Menampilkan daftar semua jadwal mengajar dosen (opsional).
     */
    public function jadwalMengajar()
    {
        $dosen = Auth::guard('dosen')->user();

        $semuaJadwal = JadwalMengajar::where('nip', $dosen->nip)
                                    ->with(['matkul', 'kelas'])
                                    ->orderBy('hari')
                                    ->get();

        return view('dosen.jadwal', compact('dosen', 'semuaJadwal'));
    }
}
