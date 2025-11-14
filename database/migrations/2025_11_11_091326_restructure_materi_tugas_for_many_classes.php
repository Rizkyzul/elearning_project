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
        Schema::table('materi', function (Blueprint $table) {
            // Hapus kolom kelas_id yang sudah tidak dipakai
            $table->dropForeign(['kelas_id']);
            $table->dropColumn('kelas_id');
        });

      Schema::create('materi_kelas', function (Blueprint $table) {
            // Kita tentukan nama tabel referensi secara eksplisit: 'materi'
            $table->foreignId('materi_id')->constrained('materi')->onDelete('cascade'); 
            $table->foreignId('kelas_id')->constrained()->onDelete('cascade');
            $table->primary(['materi_id', 'kelas_id']); 
        });

        // ==========================================
        // Buat Pivot Table untuk Tugas (tugas_kelas)
        // ==========================================
        Schema::create('tugas_kelas', function (Blueprint $table) {
            // Kita tentukan nama tabel referensi secara eksplisit: 'tugas'
            $table->foreignId('tugas_id')->constrained('tugas')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained()->onDelete('cascade');
            $table->primary(['tugas_id', 'kelas_id']);
        });

        // ==========================================
        // Buat Pivot Table untuk Tugas (tugas_kelas)
        // ==========================================
        // Schema::create('tugas_kelas', function (Blueprint $table) {
        //     $table->foreignId('tugas_id')->constrained()->onDelete('cascade');
        //     $table->foreignId('kelas_id')->constrained()->onDelete('cascade');
        //     $table->primary(['tugas_id', 'kelas_id']);
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi_kelas');
        Schema::dropIfExists('tugas_kelas');
        
        // (Opsional, mengembalikan kolom kelas_id jika diperlukan rollback penuh)
    }
};
