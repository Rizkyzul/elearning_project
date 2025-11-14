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
    Schema::table('nilai', function (Blueprint $table) {
        // Kolom untuk nilai final (rata-rata tugas, uts, uas)
        $table->float('nilai_akhir')->nullable()->after('nilai_uas');
        // Kolom untuk grade huruf
        $table->string('grade', 2)->nullable()->after('nilai_akhir');
    });
}

public function down(): void
{
    Schema::table('nilai', function (Blueprint $table) {
        $table->dropColumn('nilai_akhir');
        $table->dropColumn('grade');
    });
}
};
