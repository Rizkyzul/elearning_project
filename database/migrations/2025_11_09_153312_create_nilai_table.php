<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

     public function up(): void
        {
            Schema::create('nilai', function (Blueprint $table) {
                $table->id();
                $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
                $table->foreignId('matkul_id')->constrained('matkul')->onDelete('cascade');
                $table->float('nilai_tugas')->nullable();
                $table->float('nilai_uts')->nullable();
                $table->float('nilai_uas')->nullable();
                $table->string('catatan')->nullable();
                $table->timestamps();

                // Satu mahasiswa hanya punya 1 record nilai per matkul
                $table->unique(['mahasiswa_id', 'matkul_id']);
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};
