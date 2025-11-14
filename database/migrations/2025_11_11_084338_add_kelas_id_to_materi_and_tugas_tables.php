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
            // Buat kolom FK ke tabel 'kelas'
            // 'nullable()' artinya materi ini boleh untuk "semua kelas"
            // 'after('matkul_id')' agar rapi
            $table->foreignId('kelas_id')
                ->nullable()
                ->after('matkul_id')
                ->constrained('kelas')
                ->onDelete('set null'); // Jika kelas dihapus, materi tidak ikut hilang
        });

        Schema::table('tugas', function (Blueprint $table) {
            $table->foreignId('kelas_id')
                ->nullable()
                ->after('matkul_id')
                ->constrained('kelas')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materi_and_tugas_tables', function (Blueprint $table) {
            //
        });
    }
};
