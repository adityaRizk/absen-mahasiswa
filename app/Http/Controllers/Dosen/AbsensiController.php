<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AbsenDosen; // Model AbsenDosen adalah penanda sesi aktif
use App\Models\JadwalMengajar;

class AbsensiController extends Controller
{
    /**
     * 1. MEMBUKA SESI ABSENSI (Kunci untuk Mahasiswa)
     */
    public function bukaSesiAbsensi(Request $request, $id_jadwal)
    {
        $dosen = Auth::guard('dosen')->user();

        // 1. Verifikasi Kepemilikan Jadwal (Security Check)
        $jadwal = JadwalMengajar::where('id_jadwal', $id_jadwal)
                                ->where('nip', $dosen->nip)
                                ->first();

        if (!$jadwal) {
            return redirect()->back()->with('error', 'Akses ditolak atau jadwal tidak ditemukan.');
        }

        // 2. Cek apakah sesi untuk jadwal ini sudah aktif hari ini
        $sesiAktif = AbsenDosen::where('id_jadwal', $id_jadwal)
                               ->whereDate('jam_masuk', now()->toDateString())
                               ->first();

        if ($sesiAktif) {
            return redirect()->back()->with('warning', 'Sesi absensi untuk mata kuliah ini sudah dibuka.');
        }

        // 3. Tentukan Pertemuan Ke- (Logic Sederhana)
        // Hitung jumlah record AbsenDosen sebelumnya untuk jadwal ini
        $pertemuanKe = AbsenDosen::where('id_jadwal', $id_jadwal)->count() + 1;

        // 4. Buka Sesi (INSERT data ke tabel absen_dosen)
        AbsenDosen::create([
            'id_jadwal' => $id_jadwal,
            'pertemuan' => $pertemuanKe,
            'jam_masuk' => now(), // Waktu dosen membuka sesi
            'status' => 'Hadir',
        ]);

        return redirect()->route('dosen.absensi.sesi-aktif', $id_jadwal)->with('success', 'Sesi absensi berhasil dibuka! Mahasiswa sekarang dapat absen.');
    }


    /**
     * 2. MENUTUP SESI ABSENSI
     */
    public function tutupSesiAbsensi(Request $request, $id_jadwal)
    {
        $dosen = Auth::guard('dosen')->user();

        // Cari sesi yang aktif hari ini
        $sesiAktif = AbsenDosen::where('id_jadwal', $id_jadwal)
                               ->whereDate('jam_masuk', now()->toDateString())
                               ->whereNull('jam_keluar') // Hanya sesi yang belum ditutup
                               ->first();

        if (!$sesiAktif) {
            return redirect()->back()->with('error', 'Sesi absensi tidak ditemukan atau sudah ditutup.');
        }

        // Update jam_keluar
        $sesiAktif->update([
            'jam_keluar' => now(), // Waktu dosen menutup sesi
        ]);

        return redirect()->route('dosen.dashboard')->with('success', 'Sesi absensi berhasil ditutup.');
    }

    /**
     * 3. TAMPILKAN DAFTAR ABSEN MAHASISWA DALAM SESI AKTIF
     */
    public function sesiAktif($id_jadwal)
    {
        $sesi = AbsenDosen::where('id_jadwal', $id_jadwal)
                          ->whereDate('jam_masuk', now()->toDateString())
                          ->firstOrFail();

        // Ambil data absensi mahasiswa untuk sesi ini
        $absensiMahasiswa = $sesi->absenMahasiswa()
                                 ->with('mahasiswa') // Load data mahasiswa
                                 ->get();

        return view('dosen.sesi_aktif', compact('sesi', 'absensiMahasiswa'));
    }
}
