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
        Schema::table('persuratans', function (Blueprint $table) {
            $table->dropForeign(['pegawai_id']);
            $table->dropColumn('pegawai_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persuratans', function (Blueprint $table) {
            // 1. Tambahkan kembali kolomnya
            // Sesuaikan tipe datanya (biasanya foreignId menggunakan bigInteger unsigned)
            $table->foreignId('pegawai_id')
                  ->nullable() // Sesuaikan apakah sebelumnya boleh kosong atau tidak
                  ->constrained('kepegawaians') // Sesuaikan dengan nama tabel referensinya
                  ->onDelete('cascade'); 
        });
    }
};
