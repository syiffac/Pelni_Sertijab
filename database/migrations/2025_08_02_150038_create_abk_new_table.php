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
        Schema::create('abk_new', function (Blueprint $table) {
            $table->id(); // ID sekaligus NRP ABK
            $table->string('nama_abk'); // Nama lengkap ABK
            $table->unsignedBigInteger('id_jabatan_tetap'); // Jabatan tetap ABK
            $table->enum('status_abk', ['Organik', 'Non Organik', 'Pensiun'])->default('Organik');
            $table->timestamps();
            
            // Foreign key ke table jabatan
            $table->foreign('id_jabatan_tetap')->references('id')->on('jabatan');
            
            // Index untuk performa
            $table->index('status_abk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abk_new');
    }
};
