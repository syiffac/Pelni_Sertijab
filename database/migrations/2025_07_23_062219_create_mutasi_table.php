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
        Schema::create('mutasi', function (Blueprint $table) {
            $table->integer('id_mutasi')->primary();
            $table->integer('NRP');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('mutasi', function (Blueprint $table) {
            $table->integer('id_mutasi')->primary();
            $table->integer('nrp_turun');
            $table->integer('nrp_naik')->nullable();
            $table->integer('id_kapal_asal');
            $table->integer('id_kapal_tujuan')->nullable();
            $table->integer('id_jabatan_lama');
            $table->integer('id_jabatan_baru')->nullable();
            $table->string('case_mutasi');
            $table->string('jenis_mutasi');
            $table->string('nama_mutasi');
            $table->string('notes_mutasi')->nullable();
            $table->boolean('perlu_sertijab');
            $table->date('TMT');
            $table->date('TAT')->nullable();
            $table->timestamps();
        
            $table->foreign('nrp_turun')->references('NRP')->on('ABK');
            $table->foreign('nrp_naik')->references('NRP')->on('ABK');
            $table->foreign('id_kapal_asal')->references('id_kapal')->on('kapal');
            $table->foreign('id_kapal_tujuan')->references('id_kapal')->on('kapal');
            $table->foreign('id_jabatan_lama')->references('id_jabatan')->on('jabatan');
            $table->foreign('id_jabatan_baru')->references('id_jabatan')->on('jabatan');
        });
    }
};
