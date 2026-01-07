<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
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
     * Menampilkan form edit Dosen.
     */
    public function editDosen(Dosen $dosen)
    {
        return view('admin.datamaster.dosen.edit', compact('dosen'));
    }

    /**
     * Menyimpan perubahan data Dosen.
     */
    public function updateDosen(Request $request, Dosen $dosen)
    {
        $request->validate([
            // NIP tidak boleh diubah jika sudah ada. Jika boleh, tambahkan Rule::unique pengecualian ID.
            'nama' => 'required|string|max:40',
            // Pastikan email unik, kecuali email yang sedang dimiliki dosen ini
            'email' => ['required', 'email', 'max:40', Rule::unique('dosen', 'email')->ignore($dosen->nip, 'nip')],
            'no_telp' => 'nullable|max:15',
            'password_baru' => 'nullable|min:6', // Password opsional diubah
        ]);

        $dosen->nama = $request->nama;
        $dosen->email = $request->email;
        $dosen->no_telp = $request->no_telp;

        // Cek jika password_baru diisi, hash dan update
        if ($request->filled('password_baru')) {
            $dosen->password = Hash::make($request->password_baru);
        }

        $dosen->save();

        return redirect()->route('admin.datamaster.dosen.index')->with('success', 'Data Dosen berhasil diperbarui.');
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

    /**
     * Menampilkan form edit Kelas.
     */
    public function editKelas(Kelas $kelas)
    {
        return view('admin.datamaster.kelas.edit', compact('kelas'));
    }

    /**
     * Menyimpan perubahan data Kelas.
     */
    public function updateKelas(Request $request, Kelas $kelas)
    {
        $request->validate([
            // Kode kelas tidak diizinkan diubah jika sudah ada relasi data
            'jurusan' => 'required|string|max:30',
            'semester' => 'required|integer|min:1|max:8',
        ]);

        $kelas->jurusan = $request->jurusan;
        $kelas->semester = $request->semester;
        $kelas->save();

        return redirect()->route('admin.datamaster.kelas.index')->with('success', 'Data Kelas berhasil diperbarui.');
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
            'kode_matkul' => 'required|unique:matkul_dasar,kode_matkul|max:4',
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

    // --- Manajemen Mata Kuliah Dasar (Lanjutan) ---

    /**
     * Menampilkan form edit Mata Kuliah.
     */
    public function editMatkul(MatkulDasar $matkul)
    {
        return view('admin.datamaster.matkul.edit', compact('matkul'));
    }

    /**
     * Menyimpan perubahan data Mata Kuliah.
     */
    public function updateMatkul(Request $request, MatkulDasar $matkul)
    {
        $request->validate([
            // Kode matkul tidak diizinkan diubah jika sudah ada relasi data
            'nama_matkul' => 'required|string|max:50',
            'sks' => 'required|integer|min:1|max:6',
        ]);

        $matkul->nama_matkul = $request->nama_matkul;
        $matkul->sks = $request->sks;
        $matkul->save();

        return redirect()->route('admin.datamaster.matkul.index')->with('success', 'Data Mata Kuliah berhasil diperbarui.');
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
             'Senin',
             'Selasa',
             'Rabu',
             'Kamis',
             'Jumat',
             'Sabtu',
        ];

        return view('admin.datamaster.jadwal.create', compact('dosens', 'kelas', 'matkuls', 'daftarHari'));
    }

    /**
     * Menyimpan data Jadwal Mengajar baru.
     */
    public function storeJadwal(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nip' => 'required|exists:dosen,nip',
            'kode_kelas' => 'required|exists:kelas,kode_kelas',
            'kode_matkul' => 'required|exists:matkul_dasar,kode_matkul',
            'hari' => 'required',
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
     * Tampilkan form untuk mengedit jadwal tertentu.
     */
    public function editJadwal(JadwalMengajar $jadwal)
    {
        $dosens = Dosen::all();
        $kelas = Kelas::all();
        $matkuls = MatkulDasar::all();
        $daftarHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('admin.datamaster.jadwal.edit', compact('jadwal', 'dosens', 'kelas', 'matkuls', 'daftarHari'));
    }

    /**
     * Perbarui data jadwal di database.
     */
    public function updateJadwal(Request $request, JadwalMengajar $jadwal)
    {
        // dd($request->jam_mulai);
        $request->validate([
            'nip' => 'required|exists:dosen,nip',
            'kode_kelas' => 'required|exists:kelas,kode_kelas',
            'kode_matkul' => 'required|exists:matkul_dasar,kode_matkul',
            'hari' => ['required', Rule::in(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'])],
            'jam_mulai' => 'required|date_format:H:i:s',
            'jam_selesai' => 'required|date_format:H:i:s|after:jam_mulai',
        ]);

        // Pengecekan Konflik Jadwal
        $isConflict = JadwalMengajar::where('id_jadwal', '!=', $jadwal->id_jadwal) // Kecualikan jadwal yang sedang diedit
            ->where('kode_kelas', $request->kode_kelas)
            ->where('hari', $request->hari)
            ->where(function ($query) use ($request) {
                // Konflik jika jadwal baru dimulai di antara jadwal lama
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                      // Konflik jika jadwal baru berakhir di antara jadwal lama
                      ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                      // Konflik jika jadwal baru mencakup jadwal lama
                      ->orWhere(function ($query) use ($request) {
                          $query->where('jam_mulai', '<=', $request->jam_mulai)
                                ->where('jam_selesai', '>=', $request->jam_selesai);
                      });
            })
            ->exists();

        if ($isConflict) {
            return redirect()->back()->withInput()->with('error', 'Terjadi konflik jadwal! Kelas ini sudah memiliki mata kuliah pada hari dan jam tersebut.');
        }

        // Simpan perubahan
        $jadwal->update([
            'nip' => $request->nip,
            'kode_kelas' => $request->kode_kelas,
            'kode_matkul' => $request->kode_matkul,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()->route('admin.datamaster.jadwal.index')
                         ->with('success', 'Jadwal berhasil diperbarui!');
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

    /**
     * Menampilkan daftar semua Mahasiswa.
     */
    public function indexMahasiswa()
    {
        $mahasiswas = Mahasiswa::with('kelas')->orderBy('nim')->get();
        return view('admin.datamaster.mahasiswa.index', compact('mahasiswas'));
    }

    /**
     * Menampilkan form untuk menambah Mahasiswa baru.
     */
    public function createMahasiswa()
    {
        $kelas = Kelas::orderBy('kode_kelas')->get();
        return view('admin.datamaster.mahasiswa.create', compact('kelas'));
    }

    /**
     * Menyimpan Mahasiswa baru dari form.
     */
    public function storeMahasiswa(Request $request)
    {
        $request->validate([
            'nim' => 'required|unique:mahasiswa,nim|max:8',
            'kode_kelas' => 'required|exists:kelas,kode_kelas',
            'nama' => 'required|string|max:40',
            'tanggal_lahir' => 'nullable|date',
            'email' => 'required|email|unique:mahasiswa,email|max:35',
            'password' => 'required|min:6',
        ]);

        Mahasiswa::create([
            'nim' => $request->nim,
            'kode_kelas' => $request->kode_kelas,
            'nama' => $request->nama,
            'tanggal_lahir' => $request->tanggal_lahir,
            'email' => $request->email,
            'password' => Hash::make($request->password), // WAJIB di-hash
        ]);

        return redirect()->route('admin.datamaster.mahasiswa.index')->with('success', 'Mahasiswa baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit Mahasiswa.
     * (Sudah ada di jawaban sebelumnya)
     */
    public function editMahasiswa(Mahasiswa $mahasiswa)
    {
        $kelas = Kelas::all();
        return view('admin.datamaster.mahasiswa.edit', compact('mahasiswa', 'kelas'));
    }

    /**
     * Menyimpan perubahan data Mahasiswa.
     * (Sudah ada di jawaban sebelumnya)
     */
    public function updateMahasiswa(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'kode_kelas' => 'required|exists:kelas,kode_kelas',
            'nama' => 'required|string|max:40',
            'tanggal_lahir' => 'nullable|date',
            'email' => ['required', 'email', 'max:35', Rule::unique('mahasiswa', 'email')->ignore($mahasiswa->nim, 'nim')],
            'password_baru' => 'nullable|min:6',
        ]);

        $mahasiswa->kode_kelas = $request->kode_kelas;
        $mahasiswa->nama = $request->nama;
        $mahasiswa->tanggal_lahir = $request->tanggal_lahir;
        $mahasiswa->email = $request->email;

        if ($request->filled('password_baru')) {
            $mahasiswa->password = Hash::make($request->password_baru);
        }

        $mahasiswa->save();

        return redirect()->route('admin.datamaster.mahasiswa.index')->with('success', 'Data Mahasiswa berhasil diperbarui.');
    }

    /**
     * Menghapus Mahasiswa.
     */
    public function destroyMahasiswa(Mahasiswa $mahasiswa)
    {
        // Laravel akan otomatis menghapus relasi di absen_mahasiswa
        // jika Anda mengatur onDelete('cascade') di migration Foreign Key.
        $mahasiswa->delete();

        return redirect()->route('admin.datamaster.mahasiswa.index')->with('success', 'Data Mahasiswa berhasil dihapus.');
    }
}

