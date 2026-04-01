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
        Schema::create('usulans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('kepegawaians', 'pegawai_id')->cascadeOnDelete();
            $table->string('nama_kegiatan');
            $table->date('tanggal_kegiatan');
            $table->string('lokasi_kegiatan');
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usulans');
    }
};
