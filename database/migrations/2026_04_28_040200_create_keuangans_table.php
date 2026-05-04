<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keuangans', function (Blueprint $table) {
            $table->id();

            // Relasi langsung ke entitas utama
            $table->foreignId('usulan_id')->constrained('usulans')->onDelete('cascade');
            $table->foreignId('pegawai_id')->constrained('kepegawaians')->onDelete('cascade');

            // Detail Pembayaran
            $table->text('perincian_bayar');
            $table->decimal('nominal', 15, 2)->default(0);
            $table->integer('jumlah')->default(1);
            $table->decimal('total', 15, 2)->default(0); // Hasil nominal * jumlah

            // Kategori dan Verifikasi
            $table->enum('jenis', ['Biaya Perjalanan Dinas', 'Pengeluaran Rill', 'Keduanya'])->default('Biaya Perjalanan Dinas');
            $table->enum('status', ['pending', 'approved', 'Rejected'])->default('pending');
            $table->text('alasan_penolakan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangans');
    }
};
