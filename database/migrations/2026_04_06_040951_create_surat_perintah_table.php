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
        Schema::create('surat_perintah', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->date('tanggal_surat');
            $table->foreignId('pegawai_id')->constrained('kepegawaians')->onDelete('cascade');
            $table->string('tujuan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('keperluan');
            $table->string('tempat_berangkat');
            $table->string('tempat_tujuan');
            $table->enum('status', ['draft', 'issued', 'completed', 'cancelled'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_perintah');
    }
};
