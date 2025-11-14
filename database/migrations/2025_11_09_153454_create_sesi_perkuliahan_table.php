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
        Schema::create('sesi_perkuliahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matkul_id')->constrained('matkul')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
            $table->integer('pertemuan_ke');
            $table->string('code_masuk')->unique()->nullable();
            $table->dateTime('expires_at_masuk')->nullable();
            $table->string('code_keluar')->unique()->nullable();
            $table->dateTime('expires_at_keluar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_perkuliahan');
    }
};
