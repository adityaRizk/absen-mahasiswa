<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalMengajar extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_jadwal';
    protected $table = 'jadwal_mengajar';
    protected $fillable = [
        'kode_matkul',
        'kode_kelas',
        'nip',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    // Relasi Many-to-One
    public function matkul()
    {
        return $this->belongsTo(MatkulDasar::class, 'kode_matkul', 'kode_matkul');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kode_kelas', 'kode_kelas');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip', 'nip');
    }

    // Relasi One-to-Many ke Absensi Dosen
    public function absenDosen()
    {
        return $this->hasMany(AbsenDosen::class, 'id_jadwal', 'id_jadwal');
    }
}
