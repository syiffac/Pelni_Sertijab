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
            
            // Data Kapal - KOLOM SUDAH ADA DI FILE INI
            $table->string('id_kapal'); // Foreign key ke tabel kapal
            $table->string('nama_kapal'); // Redundant untuk performa query
            
            // ABK yang naik/masuk
            $table->unsignedBigInteger('id_abk_naik');
            $table->string('nama_lengkap_naik'); // Redundant tapi untuk performa
            $table->unsignedBigInteger('jabatan_tetap_naik'); // Jabatan tetap ABK naik
            $table->unsignedBigInteger('id_jabatan_mutasi'); // Jabatan baru hasil mutasi
            
            // ABK yang turun/keluar (nullable)
            $table->unsignedBigInteger('id_abk_turun')->nullable();
            $table->string('nama_lengkap_turun')->nullable(); // Redundant tapi untuk performa
            $table->unsignedBigInteger('jabatan_tetap_turun')->nullable(); // Jabatan tetap ABK turun
            $table->unsignedBigInteger('id_jabatan_mutasi_turun')->nullable(); // Jabatan mutasi turun
            $table->string('nama_mutasi_turun')->nullable(); // Nama mutasi turun
            $table->enum('jenis_mutasi_turun', ['Sementara', 'Definitif'])->nullable(); // Jenis mutasi turun
            $table->date('TMT_turun')->nullable(); // TMT turun
            $table->date('TAT_turun')->nullable(); // TAT turun
            
            // Data mutasi untuk ABK naik
            $table->string('nama_mutasi'); // Nama/jenis mutasi
            $table->enum('jenis_mutasi', ['Sementara', 'Definitif']); // Jenis mutasi
            
            // Tanggal untuk ABK naik
            $table->date('TMT'); // Terhitung Mulai Tanggal
            $table->date('TAT'); // Terhitung Akhir Tanggal
            
            // Dokumen/File
            $table->string('dokumen_sertijab')->nullable(); // Path file Serah Terima Jabatan
            $table->string('dokumen_familisasi')->nullable(); // Path file Berita Acara
            $table->string('dokumen_lampiran')->nullable(); // Path file lampiran lainnya
            
            // Status dan catatan
            $table->enum('status_mutasi', ['Draft', 'Disetujui', 'Ditolak', 'Selesai'])->default('Draft');
            $table->text('catatan')->nullable();
            $table->text('keterangan_turun')->nullable(); // Keterangan khusus untuk ABK turun
            $table->boolean('ada_abk_turun')->default(false); // Flag ada ABK turun
            $table->boolean('perlu_sertijab')->default(true);
            
            $table->timestamps();
            
            // Foreign keys - PERBAIKI INI
            $table->foreign('id_kapal')->references('id')->on('kapal'); // FK ke tabel kapal
            // $table->foreign('id_abk_naik')->references('id')->on('abk_new'); // Uncomment setelah tabel abk_new ada
            // $table->foreign('id_abk_turun')->references('id')->on('abk_new'); // Uncomment setelah tabel abk_new ada
            
            // PERBAIKI: Hanya tambahkan foreign key jika kolom nullable atau ada default
            $table->foreign('jabatan_tetap_naik')->references('id')->on('jabatan');
            $table->foreign('jabatan_tetap_turun')->references('id')->on('jabatan');
            $table->foreign('id_jabatan_mutasi')->references('id')->on('jabatan');
            $table->foreign('id_jabatan_mutasi_turun')->references('id')->on('jabatan');
            
            // Index untuk performa
            $table->index(['TMT', 'TAT']);
            $table->index('status_mutasi');
            $table->index('jenis_mutasi');
            $table->index('id_kapal'); // Index untuk kapal
            $table->index('id_abk_naik');
            $table->index('id_abk_turun');
            $table->index('ada_abk_turun');
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
