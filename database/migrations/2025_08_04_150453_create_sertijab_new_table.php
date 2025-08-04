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
        Schema::create('sertijab_new', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke mutasi
            $table->foreignId('id_mutasi')->constrained('mutasi_new');
            
            // Path dokumen yang diupload PUK
            $table->string('dokumen_sertijab_path')->nullable();
            $table->string('dokumen_familisasi_path')->nullable();
            $table->string('dokumen_lampiran_path')->nullable();
            
            // Status verifikasi per jenis dokumen - FIXED: Allow NULL untuk lampiran
            $table->enum('status_sertijab', ['draft', 'final'])->default('draft');
            $table->enum('status_familisasi', ['draft', 'final'])->default('draft');
            $table->enum('status_lampiran', ['draft', 'final'])->nullable(); // FIXED: Allow NULL
            
            // Status keseluruhan dokumen
            $table->enum('status_dokumen', ['draft', 'partial', 'final'])->default('draft');
            
            // Catatan untuk seluruh pasangan ABK naik-turun
            $table->text('catatan_admin')->nullable()->comment('Catatan admin untuk pasangan ABK naik-turun');
            
            // Waktu upload oleh PUK
            $table->timestamp('submitted_at')->nullable(); // Waktu PUK submit ke admin
            
            // Verifikasi admin
            $table->integer('verified_by_admin_nrp')->nullable();
            $table->timestamp('verified_at')->nullable();
            
            // Tracking perubahan
            $table->integer('updated_by_admin')->nullable(); // NRP Admin yang terakhir update
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('verified_by_admin_nrp')->references('NRP_admin')->on('users');
            $table->foreign('updated_by_admin')->references('NRP_admin')->on('users');
            
            // Indexes
            $table->index(['status_dokumen', 'submitted_at']);
            $table->index(['verified_by_admin_nrp', 'verified_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertijab_new');
    }
};