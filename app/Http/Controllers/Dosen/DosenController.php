<?php

namespace App\Http\Controllers\Dosen;

use Carbon\Carbon;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\AbsenDosen;
use Illuminate\Http\Request;
use App\Models\AbsenMahasiswa;
use App\Models\JadwalMengajar;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DosenController extends Controller
{
    /**
     * Menampilkan Dashboard Dosen dengan jadwal mengajar hari ini.
     */
    public function dashboard(){
        $dosen = Auth::guard('dosen')->user();

        // Ambil semua jadwal dosen
        $semuaJadwal = JadwalMengajar::where('nip', $dosen->nip)
                                    ->with(['matkul', 'kelas'])
                                    ->orderBy('hari')
                                    ->orderBy('jam_mulai')
                                    ->get();

        // Tentukan hari ini dalam bahasa Inggris (sesuai ENUM database)
        $hariIni = Carbon::now()->format('l'); // e.g., 'Monday'
    //    $hari_ini = now()->format('l');
    //     if($hari_ini == 'Monday'){
    //         $hari_ini = 'Senin';
    //     }else if($hari_ini == 'Tuesday'){
    //         $hari_ini = 'Selasa';
    //     }else if($hari_ini == 'Wednesday'){
    //         $hari_ini = 'Rabu';
    //     }
        return view('dosen.dashboard', compact('semuaJadwal', 'hariIni'));
    }

    /**
     * Tampilkan detail satu jadwal tertentu dan daftar pertemuannya.
     * Halaman Detail Jadwal
     */
    public function detailJadwal($id_jadwal)
    {
        $dosen = Auth::guard('dosen')->user();

        $jadwal = JadwalMengajar::where('id_jadwal', $id_jadwal)
                                ->where('nip', $dosen->nip)
                                ->with(['matkul', 'kelas', 'absenDosen' => function($query) {
                                    // Urutkan pertemuan dari yang terbaru
                                    $query->orderBy('pertemuan', 'desc');
                                }])
                                ->firstOrFail();

        // Hitung pertemuan berikutnya
        $pertemuanTerakhir = $jadwal->absenDosen->max('pertemuan') ?? 0;
        $pertemuanBerikutnya = $pertemuanTerakhir + 1;

        return view('dosen.detail_jadwal', compact('jadwal', 'pertemuanBerikutnya'));
    }

    /**
     * Tampilkan form untuk input Berita Acara dan kelola absen pertemuan tertentu.
     * Halaman Kelola Absen
     */
    public function kelolaAbsen($id_absen_dosen)
    {
        $dosen = Auth::guard('dosen')->user();

        // Pastikan sesi absen dimiliki oleh dosen yang sedang login
        $sesi = AbsenDosen::where('id_absen_dosen', $id_absen_dosen)
                            ->whereHas('jadwal', function ($query) use ($dosen) {
                                $query->where('nip', $dosen->nip);
                            })
                            ->with(['jadwal.matkul', 'jadwal.kelas', 'absenMahasiswa'])
                            ->firstOrFail();
        // dd($sesi);
        // Ambil semua mahasiswa di kelas ini (untuk memastikan semua tercantum)
        $nimMahasiswaKelas = $sesi->jadwal->kelas->mahasiswa->pluck('nim');

        // Map status absensi yang sudah ada
        $absensiTercatat = $sesi->absenMahasiswa->pluck('status', 'nim')->toArray();

        // Mahasiswa di kelas tersebut (untuk loop di view)
        $mahasiswas = Mahasiswa::whereIn('nim', $nimMahasiswaKelas)
                                ->orderBy('nim')
                                ->get();
        // dd($absensiTercatat);
        return view('dosen.kelola_absen', compact('sesi', 'mahasiswas', 'absensiTercatat'));
    }

    /**
     * Membuka Sesi Absensi baru untuk jadwal tertentu.
     */
    public function bukaSesi(Request $request, $id_jadwal)
    {
        $dosen = Auth::guard('dosen')->user();

        // 1. Validasi
        $request->validate([
            'pertemuan' => 'required|integer|min:1',
        ]);

        // 2. Pastikan dosen memiliki jadwal ini dan ambil detail waktu
        $jadwal = JadwalMengajar::where('id_jadwal', $id_jadwal)
                                ->where('nip', $dosen->nip)
                                ->firstOrFail();

        // --- ATURAN WAKTU KRUSIAL ---
        $currentTime = Carbon::now("Asia/Jakarta");
        $hariSekarang = $currentTime->isoFormat('dddd'); // Hari saat ini dalam bahasa Inggris (e.g., 'Monday')

        // Gabungkan tanggal hari ini dengan jam jadwal
        $jamMulaiJadwal = Carbon::parse($jadwal->jam_mulai,timezone:"Asia/Jakarta");
        $jamSelesaiJadwal = Carbon::parse($jadwal->jam_selesai,timezone:"Asia/Jakarta");

        $normalizedA = $currentTime->copy()->setDateFrom(now());
        $normalizedB = $jamMulaiJadwal->copy()->setDateFrom(now());
        $normalizedC = $jamSelesaiJadwal->copy()->setDateFrom(now());
        // Jika Anda ingin mengizinkan absen telat 15 menit:
        // $batasToleransi = $jamSelesaiJadwal->copy()->addMinutes(15);

        // Pengecekan 1: Apakah hari ini sesuai dengan hari jadwal?
        // dd($normalizedB);
        // dd($normalizedA, $normalizedB, $normalizedC, $normalizedA->lessThan($normalizedB), $normalizedA->greaterThan($normalizedC));
        // dd($jadwal->jam_mulai, $currentTime->format("H:i"), $jamMulaiJadwal->format('H:i'));
        if ($jadwal->hari !== $hariSekarang) {
            return redirect()->back()->with('error', 'Sesi hanya bisa dibuka pada hari jadwal yang ditentukan, yaitu hari ' . $jadwal->hari . '.');
        }

        // Pengecekan 2: Apakah waktu saat ini berada DALAM rentang waktu jadwal?
        // Dosen bisa absen (buka sesi) JIKA:
        // Waktu saat ini >= Jam Mulai jadwal
        // DAN
        // Waktu saat ini <= Jam Selesai jadwal (atau batas toleransi)
        if ($normalizedA->lessThan($normalizedB)) {
            return redirect()->back()->with('error', 'Sesi belum dapat dibuka. Waktu masuk jadwal adalah pukul ' . $jamMulaiJadwal->format('H:i') . '.');
        }

        if ($normalizedA->greaterThan($normalizedC)) {
            return redirect()->back()->with('error', 'Sesi tidak dapat dibuka. Anda sudah melewati batas jam perkuliahan pukul ' . $jamSelesaiJadwal->format('H:i') . '.');
        }
        // -----------------------------

        // 3. Pastikan belum ada sesi aktif yang belum ditutup
        $sesiAktif = AbsenDosen::where('id_jadwal', $id_jadwal)
                               ->whereNull('jam_keluar')
                               ->first();

        if ($sesiAktif) {
            return redirect()->back()->with('error', 'Sesi absensi pertemuan ke-'.$sesiAktif->pertemuan.' masih aktif. Harap ditutup terlebih dahulu.');
        }

        $sesiSelesaiHariIni = AbsenDosen::where('id_jadwal', $id_jadwal)
                                        ->whereDate('jam_masuk', $currentTime->toDateString())
                                        ->whereNotNull('jam_keluar')
                                        ->exists();

        if ($sesiSelesaiHariIni) {
            // Karena sudah ada sesi yang selesai (sudah pernah buka dan tutup) pada hari ini, tolak pembukaan sesi baru.
            return redirect()->back()->with('error', 'Absensi untuk Mata Kuliah ini sudah dilakukan (buka dan tutup) pada hari ini. Anda hanya dapat membuka sesi satu kali per hari.');
        }

        // 4. Buat Sesi Absensi Baru (AbsenDosen)
        AbsenDosen::create([
            'id_jadwal' => $id_jadwal,
            'pertemuan' => $request->pertemuan,
            'jam_masuk' => $currentTime, // Gunakan waktu absen yang valid
            'jam_keluar' => null, // Aktif
            'status' => 'Hadir',
        ]);

        return redirect()->route('dosen.detail.jadwal', $id_jadwal)->with('success', 'Sesi Pertemuan Ke-'.$request->pertemuan.' berhasil dibuka!');
    }

    /**
     * Update Berita Acara dan/atau Tutup Sesi Absensi.
     */
    public function updateSesi(Request $request, $id_absen_dosen)
    {
        $dosen = Auth::guard('dosen')->user();

        // Pastikan sesi dimiliki dosen
        $sesi = AbsenDosen::where('id_absen_dosen', $id_absen_dosen)
                          ->whereHas('jadwal', function ($query) use ($dosen) {
                              $query->where('nip', $dosen->nip);
                          })
                          ->firstOrFail();

        $request->validate([
            'keterangan' => 'required|string',
            'action' => 'required|in:update_only,tutup_sesi',
        ]);

        $sesi->keterangan = $request->keterangan;

        if ($request->action == 'tutup_sesi' && $sesi->jam_keluar === null) {
            $sesi->jam_keluar = now();
            $message = 'keterangan tersimpan dan Sesi absensi berhasil ditutup.';
        } else {
            $message = 'keterangan berhasil diperbarui.';
        }

        $sesi->save();

        return redirect()->route('dosen.kelola.absen', $id_absen_dosen)->with('success', $message);
    }

    /**
     * Update status absensi Mahasiswa secara manual.
     */
    public function updateAbsensi(Request $request, $id_absen_dosen)
    {
        $dosen = Auth::guard('dosen')->user();

        // Pastikan sesi dimiliki dosen
        $sesi = AbsenDosen::where('id_absen_dosen', $id_absen_dosen)
                          ->whereHas('jadwal', function ($query) use ($dosen) {
                              $query->where('nip', $dosen->nip);
                          })
                          ->firstOrFail();

        $request->validate([
            'status' => 'required|array',
            'status.*' => 'required|in:Hadir,Izin,Sakit,Alpa',
        ]);

        DB::transaction(function () use ($request, $sesi) {
            foreach ($request->status as $nim => $status) {
                // Hapus status Alpa jika status diubah, atau update status Hadir/Izin/Sakit
                AbsenMahasiswa::updateOrCreate(
                    [
                        'nim' => $nim,
                        'id_absen_dosen' => $sesi->id_absen_dosen
                    ],
                    [
                        'status' => $status,
                        // Jika status manual diubah ke Hadir/Izin/Sakit, isi jam_absen jika sebelumnya Alpa
                        'jam_absen' => $status != 'Alpa' ? (AbsenMahasiswa::where('nim', $nim)->where('id_absen_dosen', $sesi->id_absen_dosen)->value('jam_absen') ?? Carbon::now("Asia/Jakarta")) : null
                    ]
                );

                // Jika status diubah menjadi Alpa, dan recordnya ada, hapus recordnya
                // UPDATE: Untuk kemudahan rekap, lebih baik Alpa juga disimpan. Hanya pastikan jam_absen NULL jika Alpa.
            }
        });

        return redirect()->back()->with('success', 'Status absensi mahasiswa berhasil diperbarui.');
    }

    /**
     * Tampilkan halaman profil dosen.
     */
    public function profil()
    {
        $dosen = Auth::guard('dosen')->user();
        return view('dosen.profil', compact('dosen'));
    }
}
