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
        Schema::create('admin', function (Blueprint $table) {
            $table->id('id_admin'); // id() defaultnya auto_increment primary key
            $table->string('nama', 40);
            $table->string('username', 40);
            $table->string('email', 35)->unique(); // Pastikan email unik
            $table->string('password'); // Default Laravel untuk hash password adalah 255+
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
