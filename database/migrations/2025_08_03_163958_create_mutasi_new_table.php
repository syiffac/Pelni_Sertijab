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
        Schema::create('mutasi_new', function (Blueprint $table) {
            $table->id();
            
            // ===== DATA KAPAL =====
            // PERBAIKI: Ubah dari string ke unsignedBigInteger untuk kompatibilitas dengan kapal.id
            $table->unsignedBigInteger('id_kapal')->comment('ID Kapal tujuan mutasi');
            $table->string('nama_kapal')->comment('Nama kapal untuk performa query');
            
            // ===== ABK YANG NAIK/MASUK =====
            $table->unsignedBigInteger('id_abk_naik')->comment('ID ABK yang naik');
            $table->string('nama_lengkap_naik')->comment('Nama lengkap ABK yang naik');
            $table->unsignedBigInteger('jabatan_tetap_naik')->comment('Jabatan tetap ABK naik');
            $table->unsignedBigInteger('id_jabatan_mutasi')->comment('Jabatan baru hasil mutasi ABK naik');
            
            // Data mutasi untuk ABK naik
            $table->string('nama_mutasi')->comment('Nama/jenis mutasi ABK naik (MN, TOD, dll)');
            $table->enum('jenis_mutasi', ['Sementara', 'Definitif'])->comment('Jenis mutasi ABK naik');
            
            // Tanggal untuk ABK naik
            $table->date('TMT')->comment('Terhitung Mulai Tanggal ABK naik');
            $table->date('TAT')->comment('Terhitung Akhir Tanggal ABK naik');
            
            // ===== ABK YANG TURUN/KELUAR (NULLABLE) =====
            $table->unsignedBigInteger('id_abk_turun')->nullable()->comment('ID ABK yang turun');
            $table->string('nama_lengkap_turun')->nullable()->comment('Nama lengkap ABK yang turun');
            $table->unsignedBigInteger('jabatan_tetap_turun')->nullable()->comment('Jabatan tetap ABK turun');
            $table->unsignedBigInteger('id_jabatan_mutasi_turun')->nullable()->comment('Jabatan baru hasil mutasi ABK turun');
            
            // Data mutasi untuk ABK turun
            $table->string('nama_mutasi_turun')->nullable()->comment('Nama/jenis mutasi ABK turun (MN, TOD, dll)');
            $table->enum('jenis_mutasi_turun', ['Sementara', 'Definitif'])->nullable()->comment('Jenis mutasi ABK turun');
            
            // Tanggal untuk ABK turun
            $table->date('TMT_turun')->nullable()->comment('Terhitung Mulai Tanggal ABK turun');
            $table->date('TAT_turun')->nullable()->comment('Terhitung Akhir Tanggal ABK turun');
            
            // ===== DOKUMEN/FILE =====
            $table->string('dokumen_sertijab')->nullable()->comment('Path file Serah Terima Jabatan');
            $table->string('dokumen_familisasi')->nullable()->comment('Path file Berita Acara Familisasi');
            $table->string('dokumen_lampiran')->nullable()->comment('Path file lampiran lainnya');
            
            // ===== STATUS DAN CATATAN =====
            $table->enum('status_mutasi', ['Draft', 'Disetujui', 'Ditolak', 'Selesai'])->default('Draft')->comment('Status mutasi');
            $table->text('catatan')->nullable()->comment('Catatan umum mutasi');
            $table->text('keterangan_turun')->nullable()->comment('Keterangan khusus untuk ABK turun');
            $table->boolean('ada_abk_turun')->default(false)->comment('Flag apakah ada ABK yang turun');
            $table->boolean('perlu_sertijab')->default(true)->comment('Flag apakah perlu serah terima jabatan');
            
            $table->timestamps();
            
            // ===== FOREIGN KEYS =====
            $table->foreign('id_kapal')->references('id')->on('kapal')->onDelete('restrict');
            // $table->foreign('id_abk_naik')->references('id')->on('abk_new'); // Uncomment setelah tabel abk_new ada
            // $table->foreign('id_abk_turun')->references('id')->on('abk_new'); // Uncomment setelah tabel abk_new ada
            $table->foreign('jabatan_tetap_naik')->references('id')->on('jabatan')->onDelete('restrict');
            $table->foreign('jabatan_tetap_turun')->references('id')->on('jabatan')->onDelete('restrict');
            $table->foreign('id_jabatan_mutasi')->references('id')->on('jabatan')->onDelete('restrict');
            $table->foreign('id_jabatan_mutasi_turun')->references('id')->on('jabatan')->onDelete('restrict');
            
            // ===== INDEXES UNTUK PERFORMA =====
            $table->index('id_kapal', 'idx_mutasi_new_kapal');
            $table->index('id_abk_naik', 'idx_mutasi_new_abk_naik');
            $table->index('id_abk_turun', 'idx_mutasi_new_abk_turun');
            $table->index('status_mutasi', 'idx_mutasi_new_status');
            $table->index('jenis_mutasi', 'idx_mutasi_new_jenis');
            $table->index('ada_abk_turun', 'idx_mutasi_new_ada_abk_turun');
            $table->index(['TMT', 'TAT'], 'idx_mutasi_new_periode_naik');
            $table->index(['TMT_turun', 'TAT_turun'], 'idx_mutasi_new_periode_turun');
            $table->index('created_at', 'idx_mutasi_new_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_new');
    }
};
