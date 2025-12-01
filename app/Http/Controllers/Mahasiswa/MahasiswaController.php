<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JadwalMengajar;
use App\Models\AbsenDosen; // Model sesi absensi yang dibuka dosen
use App\Models\AbsenMahasiswa; // Model absensi mahasiswa

class MahasiswaController extends Controller
{
    /**
     * Menampilkan Dashboard Mahasiswa dengan jadwal hari ini,
     * dan status sesi absensi (Aktif/Tutup/Sudah Absen).
     */
    public function dashboard()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        // Dapatkan kode kelas mahasiswa untuk memfilter jadwal
        $kode_kelas = $mahasiswa->kode_kelas;

        // Dapatkan hari ini dalam Bahasa Inggris (misal: 'Monday')
        $hari_ini = now()->format('l');

        // Ambil jadwal mengajar untuk kelas mahasiswa hari ini
        $jadwalHariIni = JadwalMengajar::where('kode_kelas', $kode_kelas)
                                    ->where('hari', $hari_ini)
                                    ->with(['matkul', 'dosen'])
                                    ->get();

        // Cek status sesi aktif (AbsenDosen) untuk setiap jadwal
        $jadwalDenganStatus = $jadwalHariIni->map(function ($jadwal) use ($mahasiswa) {
            // Cari sesi aktif hari ini yang belum ditutup
            $sesiAktif = AbsenDosen::where('id_jadwal', $jadwal->id_jadwal)
                                            ->whereDate('jam_masuk', now()->toDateString())
                                            ->whereNull('jam_keluar')
                                            ->first();

            $jadwal->sesi_aktif = $sesiAktif;

            if ($sesiAktif) {
                // Cek apakah mahasiswa sudah absen di sesi yang aktif ini
                $jadwal->sudah_absen = AbsenMahasiswa::where('id_absen_dosen', $sesiAktif->id_absen_dosen)
                                                    ->where('nim', $mahasiswa->nim)
                                                    ->exists();
            } else {
                 $jadwal->sudah_absen = false;
            }

            return $jadwal;
        });

        return view('mahasiswa.dashboard', compact('mahasiswa', 'jadwalDenganStatus'));
    }

    /**
     * Memproses Absensi Mahasiswa (INSERT ke tabel absen_mahasiswa).
     */
    public function absen(Request $request, $id_jadwal)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $nim = $mahasiswa->nim;

        // 1. Verifikasi Sesi Aktif AbsenDosen (Harus dibuka Dosen)
        $sesiAktif = AbsenDosen::where('id_jadwal', $id_jadwal)
                               ->whereDate('jam_masuk', now()->toDateString())
                               ->whereNull('jam_keluar')
                               ->first();

        if (!$sesiAktif) {
            return redirect()->back()->with('error', 'Sesi absensi belum dibuka atau sudah ditutup oleh dosen.');
        }

        // 2. Verifikasi Absensi Ganda
        $sudahAbsen = AbsenMahasiswa::where('id_absen_dosen', $sesiAktif->id_absen_dosen)
                                    ->where('nim', $nim)
                                    ->exists();

        if ($sudahAbsen) {
            return redirect()->back()->with('warning', 'Anda sudah melakukan absensi untuk mata kuliah ini.');
        }

        // 3. Rekam Absensi
        AbsenMahasiswa::create([
            'id_absen_dosen' => $sesiAktif->id_absen_dosen,
            'nim' => $nim,
            'jam_absen' => now(),
            // Kita bisa tambahkan logika untuk status 'Izin' atau 'Sakit' jika ada form inputnya
            'status_absen' => $request->status_absen ?? 'Hadir',
        ]);

        return redirect()->back()->with('success', 'Absensi berhasil direkam! Status: Hadir.');
    }
}
