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
        Schema::create('sertijab', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_mutasi')->constrained('mutasi');
            $table->string('file_path');
            $table->string('status_verifikasi');
            $table->text('notes')->nullable();
            $table->string('keterangan_pengunggah_puk')->nullable();
            $table->timestamp('uploaded_at');
            $table->integer('verified_by_admin_nrp')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('id_mutasi')->references('id')->on('mutasi');
            $table->foreign('verified_by_admin_nrp')->references('NRP_admin')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertijab');
    }
};
