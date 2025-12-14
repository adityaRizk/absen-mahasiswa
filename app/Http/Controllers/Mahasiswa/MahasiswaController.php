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
     * Tampilkan detail absensi Mahasiswa untuk Mata Kuliah tertentu dan status tombol Absen.
     */
    public function detailMatkul($id_jadwal)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $jadwal = JadwalMengajar::where('id_jadwal', $id_jadwal)
                                ->where('kode_kelas', $mahasiswa->kode_kelas)
                                ->with('matkul', 'dosen')
                                ->firstOrFail();

        // [LOGIKA RIWAYAT ABSENSI DI SINI]
        // ... (Logika dataAbsensi sebelumnya tetap sama)

        // --- LOGIKA TOMBOL ABSEN ---
        $canAbsen = false;
        $absensiMessage = null;
        $idAbsenDosenAktif = null; // ID Sesi Dosen yang aktif, untuk digunakan di tombol Absen

        $currentTime = Carbon::now("Asia/Jakarta");
        $hariSekarang = $currentTime->isoFormat('dddd');



        // Pengecekan 1: Hari harus sesuai jadwal
        if ($jadwal->hari !== $hariSekarang) {
            $absensiMessage = 'Absensi hanya dapat dilakukan pada hari ' . $jadwal->hari . '.';
        } else {
            // Pengecekan 2: Jendela Waktu
            $jamMulaiJadwal = Carbon::parse($jadwal->jam_mulai,timezone:"Asia/Jakarta");
            $jamSelesaiJadwal = Carbon::parse($jadwal->jam_selesai,timezone:"Asia/Jakarta");

            $normalizedA = $currentTime->copy()->setDateFrom(now());
            $normalizedB = $jamMulaiJadwal->copy()->setDateFrom(now());
            $normalizedC = $jamSelesaiJadwal->copy()->setDateFrom(now());

            if ($normalizedA->lessThan($normalizedB)) {
                $absensiMessage = 'Absensi belum dibuka. Dimulai pukul ' . $jamMulaiJadwal->format('H:i') . '.';
            } elseif ($normalizedA->greaterThan($normalizedC)) {
                $absensiMessage = 'Waktu absensi telah berakhir pada pukul ' . $normalizedC->format('H:i') . '.';
            } else {
                // Pengecekan 3: Sesi Dosen Aktif
                $sesiAktif = AbsenDosen::where('id_jadwal', $id_jadwal)
                                       ->whereNull('jam_keluar') // Dosen belum menutup sesi
                                       ->first();

                if (!$sesiAktif) {
                    $absensiMessage = 'Dosen belum membuka sesi absensi untuk pertemuan ini.';
                } else {
                    $idAbsenDosenAktif = $sesiAktif->id_absen_dosen;

                    // Pengecekan 4: Sudah Absen atau Belum?
                    $sudahAbsen = AbsenMahasiswa::where('id_absen_dosen', $idAbsenDosenAktif)
                                                ->where('nim', $mahasiswa->nim)
                                                ->exists();

                    if ($sudahAbsen) {
                        $absensiMessage = 'Anda sudah mengisi daftar hadir untuk pertemuan ini.';
                    } else {
                        $absensiMessage = 'Silakan klik tombol Absen di bawah untuk mengisi daftar hadir.';
                        $canAbsen = true;
                    }
                }
            }
        }

        // Gabungkan data absensi
        $riwayatPertemuan = AbsenDosen::where('id_jadwal', $id_jadwal)
                                        // ->whereNotNull('jam_keluar')
                                        ->with(['absenMahasiswa' => function ($query) use ($mahasiswa) {
                                            $query->where('nim', $mahasiswa->nim);
                                        }])
                                        ->orderBy('pertemuan', 'asc')
                                        ->get();

        $dataAbsensi = $riwayatPertemuan->map(function ($sesi) use ($mahasiswa) {
            $absen = $sesi->absenMahasiswa->first();
            // dd($absen);
            return [
                'pertemuan' => $sesi->pertemuan,
                'tanggal' => Carbon::parse($sesi->jam_masuk)->isoFormat('D MMMM Y'),
                'keterangan_dosen' => $sesi->keterangan,
                'status_absen' => $absen->status ?? 'Alpa',
                // 'jam_absen' => '-',
                'jam_absen' => $absen ? Carbon::parse($absen->jam_absen)->format('H:i') : '-',
            ];
        });

        // Kirim semua variabel status ke View
        return view('mahasiswa.detail_matkul', compact('jadwal', 'dataAbsensi', 'canAbsen', 'absensiMessage', 'idAbsenDosenAktif'));
    }

    /**
     * Mencatat absensi Mahasiswa ke tabel absen_mahasiswa.
     */
    public function absen(Request $request, $id_absen_dosen)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        // 1. Cek Sesi Dosen aktif (Redundansi keamanan, dicek lagi di server)
        $sesi = AbsenDosen::where('id_absen_dosen', $id_absen_dosen)
                          ->whereNull('jam_keluar')
                          ->with('jadwal.kelas')
                          ->first();

        if (!$sesi) {
            return redirect()->back()->with('error', 'Sesi absensi sudah tidak aktif atau tidak ditemukan.');
        }

        // 2. Cek apakah mahasiswa ini ada di kelas yang bersangkutan
        if ($sesi->jadwal->kode_kelas !== $mahasiswa->kode_kelas) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar di kelas ini.');
        }

        // 3. Cek apakah sudah absen
        $sudahAbsen = AbsenMahasiswa::where('id_absen_dosen', $id_absen_dosen)
                                    ->where('nim', $mahasiswa->nim)
                                    ->exists();

        if ($sudahAbsen) {
            return redirect()->back()->with('error', 'Anda sudah mengisi daftar hadir sebelumnya.');
        }

        // 4. Lakukan absensi
        AbsenMahasiswa::create([
            'id_absen_dosen' => $id_absen_dosen,
            'nim' => $mahasiswa->nim,
            'jam_absen' => now(), // Catat waktu absen Mahasiswa
            'status_absen' => 'Hadir',
        ]);

        return redirect()->back()->with('success', 'Absensi berhasil dicatat! Status Anda: Hadir.');
    }
}
