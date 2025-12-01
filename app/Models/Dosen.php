<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Dosen juga perlu login
use Illuminate\Notifications\Notifiable;

class Dosen extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'nip';
    protected $table = 'dosen';
    public $incrementing = false; // NIP bukan auto-increment
    protected $keyType = 'string';

    protected $fillable = [
        'nip',
        'nama',
        'email',
        'no_telp',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    // Relasi ke Jadwal Mengajar (One-to-Many)
    public function jadwalMengajar()
    {
        return $this->hasMany(JadwalMengajar::class, 'nip', 'nip');
    }
}
