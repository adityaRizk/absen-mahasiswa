<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\AbsenDosen;
use App\Models\MatkulDasar;
use Illuminate\Http\Request;
use App\Models\AbsenMahasiswa;
use App\Models\JadwalMengajar;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LaporanController extends Controller
{
    /**
     * Menampilkan form untuk memilih Mata Kuliah dan Kelas.
     */
    public function showFormRekap()
    {
        $kelas = Kelas::orderBy('kode_kelas')->get();
        $matkuls = MatkulDasar::orderBy('nama_matkul')->get();

        return view('admin.laporan.form_rekap', compact('kelas', 'matkuls'));
    }

    /**
     * Memproses request dan menampilkan hasil Laporan Rekapitulasi.
     */
    public function generateRekap(Request $request)
    {
        $request->validate([
            'kode_kelas' => 'required|exists:kelas,kode_kelas',
            'kode_matkul' => 'required|exists:matkul_dasar,kode_matkul',
        ]);

        $kodeKelas = $request->kode_kelas;
        $kodeMatkul = $request->kode_matkul;

        // 1. Cari Jadwal yang sesuai (penting untuk menemukan semua sesi)
        $jadwal = JadwalMengajar::where('kode_kelas', $kodeKelas)
                                ->where('kode_matkul', $kodeMatkul)
                                ->with(['matkul', 'kelas', 'dosen'])
                                ->first();

        if (!$jadwal) {
            return redirect()->back()->with('error', 'Jadwal Mata Kuliah ini untuk Kelas yang dipilih tidak ditemukan.');
        }

        // 2. Cari semua Sesi Pertemuan (AbsenDosen) untuk jadwal ini
        $semuaSesi = AbsenDosen::where('id_jadwal', $jadwal->id_jadwal)
                               ->get();

        $totalPertemuan = $semuaSesi->count();

        // Dapatkan semua Mahasiswa di Kelas tersebut
        $mahasiswas = Mahasiswa::where('kode_kelas', $kodeKelas)->orderBy('nim')->get();

        // 3. Hitung Rekapitulasi per Mahasiswa
        $rekapAbsensi = $mahasiswas->map(function ($mahasiswa) use ($semuaSesi) {

            // Ambil semua ID Sesi Dosen untuk matkul dan kelas ini
            $idSesiDosen = $semuaSesi->pluck('id_absen_dosen')->toArray();

            // Hitung status absensi Mahasiswa (Hadir, Sakit, Izin, Alpa)
            $kehadiran = AbsenMahasiswa::select('status', DB::raw('count(*) as total'))
                ->where('nim', $mahasiswa->nim)
                ->whereIn('id_absen_dosen', $idSesiDosen)
                ->groupBy('status')
                ->pluck('total', 'status');

            $hadir = $kehadiran->get('Hadir', 0);
            $izin = $kehadiran->get('Izin', 0);
            $sakit = $kehadiran->get('Sakit', 0);

            // Total Absensi yang Tercatat (Hadir + Izin + Sakit)
            $totalAbsenTercatat = $hadir + $izin + $sakit;

            // Jumlah Alfa = Total Sesi - Total Absensi yang Tercatat
            $alpa = $semuaSesi->count() - $totalAbsenTercatat;

            return [
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->nama,
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpa' => $alpa,
                'persentase' => ($semuaSesi->count() > 0) ? round(($hadir / $semuaSesi->count()) * 100, 2) : 0,
            ];
        });

        return view('admin.laporan.hasil_rekap', compact('jadwal', 'totalPertemuan', 'rekapAbsensi', 'semuaSesi'));
    }
}
