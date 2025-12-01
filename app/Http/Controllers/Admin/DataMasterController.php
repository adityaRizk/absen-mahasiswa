<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\MatkulDasar;
use Illuminate\Http\Request;
use App\Models\JadwalMengajar;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class DataMasterController extends Controller
{
    /* --- Manajemen Dosen --- */

    /**
     * Menampilkan daftar semua Dosen.
     */
    public function indexDosen()
    {
        $dosens = Dosen::orderBy('nama')->get();
        return view('admin.datamaster.dosen.index', compact('dosens'));
    }

    /**
     * Menampilkan form tambah Dosen.
     */
    public function createDosen()
    {
        return view('admin.datamaster.dosen.create');
    }

    /**
     * Menyimpan data Dosen baru.
     */
    public function storeDosen(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:dosen,nip|max:18',
            'nama' => 'required|string|max:40',
            'email' => 'required|email|unique:dosen,email|max:40',
            'password' => 'required|min:6',
        ]);

        Dosen::create([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => Hash::make($request->password), // WAJIB di-hash!
        ]);

        return redirect()->route('admin.datamaster.dosen.index')->with('success', 'Data Dosen berhasil ditambahkan.');
    }

    /**
     * Menghapus data Dosen.
     */
    public function destroyDosen(Dosen $dosen)
    {
        // Gunakan Eloquent untuk menghapus
        $dosen->delete();

        // Catatan: Karena kita menggunakan onDelete('cascade') di migration,
        // semua jadwal mengajar yang terikat dengan Dosen ini akan ikut terhapus.

        return redirect()->route('admin.datamaster.dosen.index')->with('success', 'Data Dosen berhasil dihapus.');
    }

    /**
     * Menampilkan daftar semua Kelas.
     */
    public function indexKelas()
    {
        $kelas = Kelas::orderBy('kode_kelas')->get();
        return view('admin.datamaster.kelas.index', compact('kelas'));
    }

    /**
     * Menampilkan form tambah Kelas.
     */
    public function createKelas()
    {
        return view('admin.datamaster.kelas.create');
    }

    /**
     * Menyimpan data Kelas baru.
     */
    public function storeKelas(Request $request)
    {
        $request->validate([
            'kode_kelas' => 'required|unique:kelas,kode_kelas|max:4',
            'jurusan' => 'required|string|max:30',
            'semester' => 'required|integer|min:1|max:8',
        ]);

        Kelas::create([
            'kode_kelas' => $request->kode_kelas,
            'jurusan' => $request->jurusan,
            'semester' => $request->semester,
        ]);

        return redirect()->route('admin.datamaster.kelas.index')->with('success', 'Data Kelas berhasil ditambahkan.');
    }

    /**
     * Menghapus data Kelas.
     */
    public function destroyKelas(Kelas $kelas)
    {
        // Karena ada Foreign Key di tabel mahasiswa dan jadwal_mengajar
        // dengan onDelete('cascade'), penghapusan kelas akan menghapus semua
        // mahasiswa dan jadwal yang terikat. (Harus hati-hati di production!)
        $kelas->delete();
        return redirect()->route('admin.datamaster.kelas.index')->with('success', 'Data Kelas berhasil dihapus.');
    }

    // --- Manajemen Mata Kuliah Dasar ---

    /**
     * Menampilkan daftar semua Mata Kuliah Dasar.
     */
    public function indexMatkul()
    {
        $matkuls = MatkulDasar::orderBy('kode_matkul')->get();
        return view('admin.datamaster.matkul.index', compact('matkuls'));
    }

    /**
     * Menampilkan form tambah Mata Kuliah.
     */
    public function createMatkul()
    {
        return view('admin.datamaster.matkul.create');
    }

    /**
     * Menyimpan data Mata Kuliah baru.
     */
    public function storeMatkul(Request $request)
    {
        $request->validate([
            'kode_matkul' => 'required|unique:matkul_dasar,kode_matkul|max:10',
            'nama_matkul' => 'required|string|max:50',
            'sks' => 'required|integer|min:1|max:6',
        ]);

        MatkulDasar::create([
            'kode_matkul' => $request->kode_matkul,
            'nama_matkul' => $request->nama_matkul,
            'sks' => $request->sks,
        ]);

        return redirect()->route('admin.datamaster.matkul.index')->with('success', 'Data Mata Kuliah berhasil ditambahkan.');
    }

    /**
     * Menghapus data Mata Kuliah.
     */
    public function destroyMatkul(MatkulDasar $matkul)
    {
        // Penghapusan matkul akan menghapus semua jadwal_mengajar yang terikat
        $matkul->delete();
        return redirect()->route('admin.datamaster.matkul.index')->with('success', 'Data Mata Kuliah berhasil dihapus.');
    }

    /**
     * Menampilkan daftar semua Jadwal Mengajar.
     */
    public function indexJadwal()
    {
        // Mengambil semua jadwal dan memuat data relasi (Dosen, Matkul, Kelas)
        $jadwals = JadwalMengajar::with(['dosen', 'matkul', 'kelas'])->orderBy('hari')->get();
        return view('admin.datamaster.jadwal.index', compact('jadwals'));
    }

    /**
     * Menampilkan form tambah Jadwal Mengajar.
     * Membutuhkan data Master Dosen, Kelas, dan Matkul untuk dropdown.
     */
    public function createJadwal()
    {
        $dosens = Dosen::orderBy('nama')->get();
        $kelas = Kelas::orderBy('kode_kelas')->get();
        $matkuls = MatkulDasar::orderBy('nama_matkul')->get();

        // Daftar hari dalam format yang sesuai dengan skema database ('Monday', 'Tuesday', dll.)
        $daftarHari = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        return view('admin.datamaster.jadwal.create', compact('dosens', 'kelas', 'matkuls', 'daftarHari'));
    }

    /**
     * Menyimpan data Jadwal Mengajar baru.
     */
    public function storeJadwal(Request $request)
    {
        $request->validate([
            'nip' => 'required|exists:dosen,nip',
            'kode_kelas' => 'required|exists:kelas,kode_kelas',
            'kode_matkul' => 'required|exists:matkul_dasar,kode_matkul',
            'hari' => ['required', Rule::in(array_keys($this->getDaftarHari()))], // Memastikan input hari valid
            'jam_mulai' => 'required|date_format:H:i:s',
            'jam_selesai' => 'required|date_format:H:i:s|after:jam_mulai',
            'ruangan' => 'nullable|string|max:10',
        ]);

        // Pencegahan Duplikasi: Cek apakah Matkul dan Kelas sudah memiliki jadwal di hari yang sama.
        $isDuplicate = JadwalMengajar::where('kode_kelas', $request->kode_kelas)
                                     ->where('kode_matkul', $request->kode_matkul)
                                     ->where('hari', $request->hari)
                                     ->exists();

        if ($isDuplicate) {
             return redirect()->back()->withInput()->with('error', 'Jadwal untuk Mata Kuliah dan Kelas yang sama pada hari yang sama sudah ada.')->withErrors(['kode_matkul' => 'Duplikasi jadwal terdeteksi.']);
        }

        JadwalMengajar::create($request->only([
            'nip',
            'kode_kelas',
            'kode_matkul',
            'hari',
            'jam_mulai',
            'jam_selesai',
            'ruangan'
        ]));

        return redirect()->route('admin.datamaster.jadwal.index')->with('success', 'Jadwal Mengajar berhasil ditambahkan.');
    }

    /**
     * Menghapus data Jadwal Mengajar.
     */
    public function destroyJadwal(JadwalMengajar $jadwal)
    {
        $id_jadwal = $jadwal->id_jadwal;

        $jadwal->delete();

        // Catatan: Data absensi Mahasiswa dan Dosen yang terikat
        // dengan ID jadwal ini akan otomatis terhapus karena 'onDelete('cascade')'.

        return redirect()->route('admin.datamaster.jadwal.index')->with('success', 'Jadwal Mengajar (ID: ' . $id_jadwal . ') berhasil dihapus.');
    }

    /**
     * Helper untuk daftar hari.
     */
    private function getDaftarHari()
    {
        return [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
    }

}

