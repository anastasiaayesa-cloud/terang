<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usulans', function (Blueprint $table) {
            // Menambahkan kolom sampai_tanggal setelah tanggal_kegiatan
            $table->date('sampai_tanggal')->after('tanggal_kegiatan');
        });
    }

    public function down(): void
    {
        Schema::table('usulans', function (Blueprint $table) {
            $table->dropColumn('sampai_tanggal');
        });
    }
};
