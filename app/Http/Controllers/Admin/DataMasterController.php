<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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

    /* * Tambahkan fungsi indexMahasiswa, storeMahasiswa, dll.
     * Logika CRUD untuk Mahasiswa, Kelas, dan Mata Kuliah Dasar akan mengikuti pola di atas.
     */
}
