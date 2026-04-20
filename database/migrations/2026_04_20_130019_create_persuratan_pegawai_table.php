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
        Schema::create('persuratan_pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persuratan_id')->constrained('persuratans')->onDelete('cascade');
            $table->foreignId('pegawai_id')->constrained('kepegawaians')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persuratan_pegawai');
    }
};
