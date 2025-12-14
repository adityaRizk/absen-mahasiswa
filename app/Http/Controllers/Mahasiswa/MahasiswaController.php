<?php

namespace App\Http\Controllers\Mahasiswa;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\JadwalMengajar;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AbsenMahasiswa; // Model absensi mahasiswa
use App\Models\AbsenDosen; // Model sesi absensi yang dibuka dosen

class MahasiswaController extends Controller
{
    /**
     * Tampilkan Dashboard Mahasiswa: List Semua Mata Kuliah yang diambil di kelasnya.
     * Logika lama (jadwal hari ini) dihapus/digantikan.
     */
    public function dashboard()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        // Ambil SEMUA jadwal (Mata Kuliah) yang dimiliki kelas Mahasiswa ini
        $semuaJadwal = JadwalMengajar::where('kode_kelas', $mahasiswa->kode_kelas)
                                    ->with('matkul', 'dosen')
                                    ->orderBy('hari')
                                    ->orderBy('jam_mulai')
                                    ->get();

        return view('mahasiswa.dashboard', compact('mahasiswa', 'semuaJadwal'));
    }

    /**
     * Tampilkan detail absensi Mahasiswa untuk Mata Kuliah tertentu.
     * Halaman Detail Mata Kuliah
     */
    public function detailMatkul($id_jadwal)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        // 1. Ambil Jadwal Mata Kuliah
        $jadwal = JadwalMengajar::where('id_jadwal', $id_jadwal)
                                ->where('kode_kelas', $mahasiswa->kode_kelas)
                                ->with('matkul', 'dosen')
                                ->firstOrFail();

        // 2. Ambil Riwayat Sesi Pertemuan Dosen (AbsenDosen) untuk jadwal ini
        //    dan relasikan dengan AbsenMahasiswa untuk Mahasiswa yang sedang login.
        $riwayatPertemuan = AbsenDosen::where('id_jadwal', $id_jadwal)
                                        ->whereNotNull('jam_keluar') // Hanya pertemuan yang sudah SELESAI
                                        ->with(['absenMahasiswa' => function ($query) use ($mahasiswa) {
                                            $query->where('nim', $mahasiswa->nim);
                                        }])
                                        ->orderBy('pertemuan', 'asc')
                                        ->get();

        // 3. Gabungkan data absensi
        $dataAbsensi = $riwayatPertemuan->map(function ($sesi) use ($mahasiswa) {
            $absen = $sesi->absenMahasiswa->first();

            return [
                'pertemuan' => $sesi->pertemuan,
                'tanggal' => Carbon::parse($sesi->jam_masuk)->isoFormat('D MMMM Y'),
                'keterangan_dosen' => $sesi->keterangan, // Menggunakan kolom 'keterangan' yang baru
                'status_absen' => $absen->status_absen ?? 'Alpa', // Default 'Alpa' jika tidak ada record
                // 'jam_absen' => $absen->jam_absen !== null? Carbon::parse($absen->jam_absen)->format('H:i') : '-',
                'jam_absen' => '-',
            ];
        });

        return view('mahasiswa.detail_matkul', compact('jadwal', 'dataAbsensi'));
    }

    /**
     * Memproses Absensi Mahasiswa (INSERT ke tabel absen_mahasiswa).
     */
    public function absen(Request $request, $id_jadwal)
    {
         $sesiAktif = AbsenDosen::where('id_jadwal', $id_jadwal)
                        ->whereNull('jam_keluar') // Harus aktif (belum ditutup dosen)
                        ->with('jadwal')
                        ->first();

        if (!$sesiAktif) {
            return redirect()->back()->with('error', 'Sesi absensi belum dibuka atau sudah ditutup oleh Dosen.');
        }

        // 2. CEK BATAS WAKTU (Integritas Mahasiswa)
        $currentTime = Carbon::now();
        $jamSelesaiJadwal = Carbon::parse($sesiAktif->jadwal->jam_selesai);

        // Tambahkan toleransi 15 menit (atau sesuai kebijakan)
        $batasAkhirAbsen = $jamSelesaiJadwal->copy()->addMinutes(15);

        if ($currentTime->greaterThan($batasAkhirAbsen)) {
            // Tutup sesi secara otomatis jika terlewat jauh (opsional)
            // $sesiAktif->update(['jam_keluar' => $jamSelesaiJadwal]);

            return redirect()->back()->with('error', 'Waktu absensi telah berakhir (batas toleransi sampai ' . $batasAkhirAbsen->format('H:i') . ').');
        }
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
