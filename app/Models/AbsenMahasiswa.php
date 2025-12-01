<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenMahasiswa extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_absen_mahasiswa';
    protected $table = 'absen_mahasiswa';
    protected $fillable = [
        'nim',
        'id_absen_dosen',
        'jam_absen',
        'status',
    ];

    // Relasi Many-to-One ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    // Relasi Many-to-One ke Sesi Absen Dosen (KUNCI UTAMA)
    // Mahasiswa absen berdasarkan sesi yang dibuka oleh dosen.
    public function sesiDosen()
    {
        return $this->belongsTo(AbsenDosen::class, 'id_absen_dosen', 'id_absen_dosen');
    }
}
