<?php
// filepath: c:\laragon\www\SertijabPelni\database\migrations\2025_07_23_062219_create_mutasi_table.php

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
        Schema::create('mutasi', function (Blueprint $table) {
            $table->id();
            
            // ABK yang naik/masuk
            $table->unsignedBigInteger('id_abk_naik');
            $table->string('nama_lengkap_naik'); // Redundant tapi untuk performa
            $table->unsignedBigInteger('jabatan_tetap_naik'); // Jabatan tetap ABK naik
            $table->unsignedBigInteger('id_jabatan_mutasi'); // Jabatan baru hasil mutasi
            
            // ABK yang turun/keluar (nullable)
            $table->unsignedBigInteger('id_abk_turun')->nullable();
            $table->string('nama_lengkap_turun')->nullable(); // Redundant tapi untuk performa
            $table->unsignedBigInteger('jabatan_tetap_turun')->nullable(); // Jabatan tetap ABK turun
            
            // Data mutasi
            $table->string('nama_mutasi'); // Nama/jenis mutasi - TETAP ADA
            $table->enum('jenis_mutasi', ['Sementara', 'Definitif']); // TETAP ADA
            
            // Tanggal
            $table->date('TMT'); // Terhitung Mulai Tanggal
            $table->date('TAT'); // Terhitung Akhir Tanggal
            
            // Dokumen/File
            $table->string('dokumen_sertijab')->nullable(); // Path file Serah Terima Jabatan
            $table->string('dokumen_familisasi')->nullable(); // Path file Berita Acara
            $table->string('dokumen_lampiran')->nullable(); // Path file lampiran lainnya
            
            // Status dan catatan
            $table->enum('status_mutasi', ['Draft', 'Disetujui', 'Ditolak', 'Selesai'])->default('Draft');
            $table->text('catatan')->nullable();
            $table->boolean('perlu_sertijab')->default(true);
            
            $table->timestamps();
            
            // Foreign keys - COMMENT DULU KARENA TABLE abk_new BELUM ADA
            // $table->foreign('id_abk_naik')->references('id')->on('abk_new');
            // $table->foreign('id_abk_turun')->references('id')->on('abk_new');
            $table->foreign('jabatan_tetap_naik')->references('id')->on('jabatan');
            $table->foreign('jabatan_tetap_turun')->references('id')->on('jabatan');
            $table->foreign('id_jabatan_mutasi')->references('id')->on('jabatan');
            
            // Index untuk performa
            $table->index(['TMT', 'TAT']);
            $table->index('status_mutasi');
            $table->index('jenis_mutasi'); // TETAP ADA
            $table->index('id_abk_naik');
            $table->index('id_abk_turun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi');
    }
};
