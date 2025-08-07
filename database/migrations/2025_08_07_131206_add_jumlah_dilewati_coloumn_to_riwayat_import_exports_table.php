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
        Schema::table('riwayat_import_exports', function (Blueprint $table) {
            $table->integer('jumlah_dilewati')->after('jumlah_gagal')->default(0);
            $table->text('keterangan')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_import_exports', function (Blueprint $table) {
            $table->dropColumn('jumlah_dilewati');
            $table->string('keterangan')->change(); // Kembalikan ke string
        });
    }
};
