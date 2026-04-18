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
            // Tambahkan nullable() jika tabel persuratans sudah ada isinya agar tidak error saat migrate
            $table->foreignId('usulan_id')->nullable()->after('pegawai_id')->constrained('usulans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('persuratans', function (Blueprint $table) {
            // Menghapus constraint dan kolom saat rollback
            $table->dropForeign(['usulan_id']);
            $table->dropColumn('usulan_id');
        });
    }
};
