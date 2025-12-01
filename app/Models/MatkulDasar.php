<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatkulDasar extends Model
{
    use HasFactory;

    protected $primaryKey = 'kode_matkul';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'matkul_dasar';
    
    protected $fillable = [
        'kode_matkul',
        'nama_matkul',
        'sks',
    ];

    // Relasi ke Jadwal Mengajar (One-to-Many)
    public function jadwalMengajar()
    {
        return $this->hasMany(JadwalMengajar::class, 'kode_matkul', 'kode_matkul');
    }
}
