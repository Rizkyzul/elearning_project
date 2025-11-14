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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom role setelah 'email'
            $table->enum('role', ['dosen', 'mahasiswa'])
                ->after('email');

            // Tambahkan flag ganti password setelah 'password'
            $table->boolean('must_change_password')
                ->default(true)
                ->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('must_change_password');
        });
    }
};
