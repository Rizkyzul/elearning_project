<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Ubah enum 'role' untuk menambahkan 'superadmin'
        $table->enum('role', ['superadmin', 'dosen', 'mahasiswa'])
              ->default('mahasiswa')
              ->change();
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Kembalikan jika di-rollback
        $table->enum('role', ['dosen', 'mahasiswa'])
              ->change();
    });
}
};
