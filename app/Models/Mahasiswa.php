<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Mahasiswa juga perlu login

class Mahasiswa extends Authenticatable
{
    use HasFactory;

    protected $primaryKey = 'nim';
    protected $table = 'mahasiswa';
    public $incrementing = false; // NIM bukan auto-increment
    protected $keyType = 'string';

    protected $fillable = [
        'nim',
        'kode_kelas',
        'nama',
        'tanggal_lahir',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    // Relasi ke Kelas (Many-to-One)
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kode_kelas', 'kode_kelas');
    }

    // Relasi ke Absen Mahasiswa (One-to-Many)
    public function absensi()
    {
        return $this->hasMany(AbsenMahasiswa::class, 'nim', 'nim');
    }
}
