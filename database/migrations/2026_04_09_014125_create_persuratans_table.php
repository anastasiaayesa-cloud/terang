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
        Schema::create('persuratans', function (Blueprint $table) {
            // 1. ID Utama (Primary Key)
            $table->id();

            // 2. Foreign Key ke tabel pegawais
            // Pastikan nama tabelnya adalah 'pegawais' (jamak) sesuai standar Laravel
            $table->foreignId('pegawai_id')
                ->constrained('kepegawaians')
                ->onDelete('cascade');

            // 3. Kolom Informasi Surat
            $table->string('nama_surat');
            $table->string('file_pdf');
            $table->date('tanggal_upload');
            $table->string('perihal');

            // 4. Jenis Anggaran (Enum dengan 3 opsi)
            $table->enum('jenis_anggaran', ['BPMP', 'LUAR BPMP', 'GABUNGAN']);

            // 5. Timestamps (created_at & updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persuratans');
    }
};
