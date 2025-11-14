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
        Schema::create('dosen_matkul', function (Blueprint $table) {
            $table->primary(['dosen_id', 'matkul_id']); // Composite primary key
            $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
            $table->foreignId('matkul_id')->constrained('matkul')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_matkul');
    }
};
