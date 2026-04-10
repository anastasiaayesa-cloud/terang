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
        Schema::create('laporan_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('kepegawaians', 'id')->cascadeOnDelete();
            $table->foreignId('perencanaan_id')->constrained('perencanaans')->cascadeOnDelete();
            $table->string('judul_laporan');
            $table->text('deskripsi_kegiatan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('lokasi_kegiatan');
            $table->string('file_laporan');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan_reviewer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kegiatans');
    }
};
