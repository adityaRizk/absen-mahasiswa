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
        Schema::create('absen_mahasiswa', function (Blueprint $table) {
            $table->id('id_absen_mahasiswa');
            $table->char('nim', 8);
            $table->unsignedBigInteger('id_absen_dosen'); // Kunci yang menandakan sesi absensi aktif oleh dosen

            $table->dateTime('jam_absen')->nullable();
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alpa']);
            $table->timestamps();

            // Foreign Keys
            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('id_absen_dosen')->references('id_absen_dosen')->on('absen_dosen')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absen_mahasiswa');
    }
};
