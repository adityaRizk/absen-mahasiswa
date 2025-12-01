<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absen_dosen', function (Blueprint $table) {
            $table->id('id_absen_dosen');
            $table->unsignedBigInteger('id_jadwal');
            $table->unsignedTinyInteger('pertemuan'); // Pertemuan ke-1, ke-2, dst.
            $table->dateTime('jam_masuk'); // Waktu dosen mulai/buka absensi
            $table->dateTime('jam_keluar')->nullable(); // Waktu dosen selesai/tutup absensi (opsional)
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Digantikan']); // Status kehadiran dosen
            $table->timestamps();

            // Foreign Key
            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwal_mengajar')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absen_dosen');
    }
};
