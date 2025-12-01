<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MatkulDasar;
use App\Models\JadwalMengajar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Admin::insert([
            [
                'username' => 'admin',
                'nama' => 'Super Admin',
                'email' => 'admin@kampus.ac.id',
                'password' => Hash::make('admin123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'admin123',
                'nama' => 'Petugas TU',
                'email' => 'petugas@kampus.ac.id',
                'password' => Hash::make('tu456'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);


        Dosen::insert([
            [
                'nip' => '197001012000011001',
                'nama' => 'Dr. Agung Cahyono, S.Kom., M.T.',
                'email' => 'agung@dosen.ac.id',
                'no_telp' => '081234567890',
                'password' => Hash::make('dosen123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '198510202015032002',
                'nama' => 'Ir. Budi Santoso, M.Eng.',
                'email' => 'budi@dosen.ac.id',
                'no_telp' => '087654321098',
                'password' => Hash::make('dosen123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Kelas::insert([
            [
                'kode_kelas' => 'TI2A',
                'jurusan' => 'Teknik Informatika',
                'semester' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_kelas' => 'SI4B',
                'jurusan' => 'Sistem Informasi',
                'semester' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        MatkulDasar::insert([
            [
                'kode_matkul' => 'BD40',
                'nama_matkul' => 'Basis Data',
                'sks' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_matkul' => 'WE30',
                'nama_matkul' => 'Pemrograman Web',
                'sks' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        JadwalMengajar::insert([
            [
                // Jadwal 1
                'kode_matkul' => 'BD40',
                'kode_kelas' => 'TI2A',
                'nip' => '197001012000011001',
                'hari' => 'Senin',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // Jadwal 2
                'kode_matkul' => 'WE30',
                'kode_kelas' => 'SI4B',
                'nip' => '198510202015032002',
                'hari' => 'Rabu',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '15:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Mahasiswa::insert([
            // Mahasiswa 1 - Kelas TI2A
            [
                'nim' => '20230001',
                'kode_kelas' => 'TI2A',
                'nama' => 'Rizky Pratama',
                'tanggal_lahir' => '2005-01-15',
                'email' => 'rizky.p@mhs.ac.id',
                'password' => Hash::make('mhs12345'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Mahasiswa 2 - Kelas TI2A
            [
                'nim' => '20230002',
                'kode_kelas' => 'TI2A',
                'nama' => 'Siti Aisyah',
                'tanggal_lahir' => '2004-11-20',
                'email' => 'siti.a@mhs.ac.id',
                'password' => Hash::make('mhs12345'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Mahasiswa 3 - Kelas SI4B
            [
                'nim' => '20220050',
                'kode_kelas' => 'SI4B',
                'nama' => 'Bambang Sudarsono',
                'tanggal_lahir' => '2003-08-05',
                'email' => 'bambang.s@mhs.ac.id',
                'password' => Hash::make('mhs12345'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);


    }
}
