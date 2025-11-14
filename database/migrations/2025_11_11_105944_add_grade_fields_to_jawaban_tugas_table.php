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
    Schema::table('jawaban_tugas', function (Blueprint $table) {
        // Kolom untuk nilai individual tugas
        $table->float('nilai_dosen')->nullable()->after('updated_at');
        // Kolom untuk catatan/feedback tugas
        $table->text('catatan_dosen')->nullable()->after('nilai_dosen');
    });
}

public function down(): void
{
    Schema::table('jawaban_tugas', function (Blueprint $table) {
        $table->dropColumn('nilai_dosen');
        $table->dropColumn('catatan_dosen');
    });
}
};
