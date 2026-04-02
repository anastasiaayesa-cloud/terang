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
        Schema::create('bukti_pengeluarans', function (Blueprint $table) {
            $table->id();

            // Relasi ke Perencanaan
            $table->unsignedBigInteger('perencanaan_id');

            // Relasi ke Pegawai (yang upload)
            $table->unsignedBigInteger('pegawai_id')->nullable();

            // Tipe bukti pengeluaran
            $table->enum('tipe_bukti', [
                'tiket_pesawat',
                'tiket_kapal',
                'tiket_kereta',
                'tiket_taxi',
                'tiket_hotel',
                'bukti_lainnya',
            ]);

            // File upload (PDF/Image)
            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable(); // pdf, jpg, png, etc
            $table->unsignedBigInteger('file_size')->nullable(); // in bytes

            // Keterangan tambahan
            $table->text('keterangan')->nullable();
            $table->decimal('nominal', 15, 2)->nullable();
            $table->date('tanggal_bukti')->nullable();

            $table->timestamps();

            // Foreign Keys
            $table->foreign('perencanaan_id')->references('id')->on('perencanaans')->cascadeOnDelete();
            $table->foreign('pegawai_id')->references('id')->on('kepegawaians')->nullOnDelete();

            // Index untuk performa
            $table->index('perencanaan_id');
            $table->index('pegawai_id');
            $table->index('tipe_bukti');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukti_pengeluarans');
    }
};
