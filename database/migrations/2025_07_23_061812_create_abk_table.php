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
        Schema::create('abk', function (Blueprint $table) {
            $table->id();
            $table->integer('NRP')->primary();
            $table->string('nama_abk');
            $table->string('status_abk');
            $table->unsignedBigInteger('id_kapal');
            $table->unsignedBigInteger('id_jabatan');
            $table->foreign('id_kapal')->references('id')->on('kapal')->onDelete('cascade');
            $table->foreign('id_jabatan')->references('id')->on('jabatan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abk');
    }
};
