<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_kegiatans', function (Blueprint $table) {
            $table->foreignId('usulan_id')
                ->nullable()
                ->constrained('usulans')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('laporan_kegiatans', function (Blueprint $table) {
            $table->dropForeign(['usulan_id']);
            $table->dropColumn('usulan_id');
        });
    }
};