<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */public function up(): void
{
    Schema::table('sesi_perkuliahan', function (Blueprint $table) {
        $table->foreignId('kelas_id')
              ->nullable()
              ->after('dosen_id') // Taruh setelah dosen_id
              ->constrained('kelas')
              ->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('sesi_perkuliahan', function (Blueprint $table) {
        $table->dropForeign(['kelas_id']);
        $table->dropColumn('kelas_id');
    });
}
};
