<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenDosen extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_absen_dosen';
    protected $table = 'absen_dosen';
    protected $fillable = [
        'id_jadwal',
        'pertemuan',
        'jam_masuk',
        'jam_keluar',
        'status',
    ];

    // Relasi Many-to-One ke Jadwal Mengajar
    public function jadwal()
    {
        return $this->belongsTo(JadwalMengajar::class, 'id_jadwal', 'id_jadwal');
    }

    // Relasi One-to-Many ke Absensi Mahasiswa
    // Ini menunjukkan sesi absensi mana yang menghasilkan record absensi mahasiswa.
    public function absenMahasiswa()
    {
        return $this->hasMany(AbsenMahasiswa::class, 'id_absen_dosen', 'id_absen_dosen');
    }
}
