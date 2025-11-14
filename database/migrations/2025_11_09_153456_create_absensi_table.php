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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_perkuliahan_id')->constrained('sesi_perkuliahan')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->dateTime('scan_masuk')->nullable();
            $table->dateTime('scan_keluar')->nullable();
            $table->enum('status', ['hadir', 'terlambat', 'absen', 'keluar_tanpa_masuk']);
            $table->timestamps();

            // Satu mahasiswa hanya bisa absen 1x per sesi
            $table->unique(['sesi_perkuliahan_id', 'mahasiswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
