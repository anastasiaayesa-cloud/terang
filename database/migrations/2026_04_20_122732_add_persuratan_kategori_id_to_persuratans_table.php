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
            // Menambahkan kolom foreign key setelah kolom perencanaan_id
            $table->foreignId('persuratan_kategori_id')
                  ->after('perencanaan_id') 
                  ->constrained('persuratan_kategoris')
                  ->onDelete('cascade'); // Jika kategori dihapus, surat terkait ikut terhapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persuratans', function (Blueprint $table) {
            // Menghapus constraint dan kolom jika rollback
            $table->dropForeign(['persuratan_kategori_id']);
            $table->dropColumn('persuratan_kategori_id');
        });
    }
};
