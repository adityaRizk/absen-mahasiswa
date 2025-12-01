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
        Schema::create('matkul_dasar', function (Blueprint $table) {
            $table->char('kode_matkul', 4)->primary();
            $table->string('nama_matkul', 50);
            $table->unsignedTinyInteger('sks'); // Satuan Kredit Semester
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matkul_dasar');
    }
};
