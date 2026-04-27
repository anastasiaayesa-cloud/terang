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
        Schema::table('bukti_pengeluarans', function (Blueprint $table) {
            // Letakkan setelah kolom perencanaan_id agar rapi
            $table->unsignedBigInteger('usulan_id')->nullable()->after('perencanaan_id');
            
            // Jika usulan_id adalah foreign key ke tabel usulans:
            $table->foreign('usulan_id')->references('id')->on('usulans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('bukti_pengeluarans', function (Blueprint $table) {
            $table->dropColumn('usulan_id');
        });
    }
};
