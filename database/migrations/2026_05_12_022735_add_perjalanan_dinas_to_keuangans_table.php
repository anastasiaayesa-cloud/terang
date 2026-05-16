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
        Schema::table('keuangans', function (Blueprint $table) {
            $table->text('maksud_perjalanan_dinas')->nullable()->after('tanggal_kwitansi');
            $table->string('alat_angkut', 255)->nullable()->after('maksud_perjalanan_dinas');
            $table->string('tempat_berangkat', 255)->nullable()->after('alat_angkut');
            $table->string('tempat_tujuan', 255)->nullable()->after('tempat_berangkat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keuangans', function (Blueprint $table) {
            //
        });
    }
};
