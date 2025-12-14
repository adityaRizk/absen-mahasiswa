<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $primaryKey = 'kode_kelas';
    protected $table = 'kelas';
    public $incrementing = false; // Kode Kelas bukan auto-increment
    protected $keyType = 'string';

    protected $fillable = [
        'kode_kelas',
        'jurusan',
        'semester',
    ];

    // Relasi ke Mahasiswa (One-to-Many)
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'kode_kelas', 'kode_kelas');
    }

    // Relasi ke Jadwal Mengajar (One-to-Many)
    public function jadwalMengajar()
    {
        return $this->hasMany(JadwalMengajar::class, 'kode_kelas', 'kode_kelas');
    }
}
