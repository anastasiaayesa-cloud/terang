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
        Schema::create('usulan_pembayarans', function (Blueprint $table) {
            $table->id();

            // Relasi ke Perencanaan dan Pegawai
            $table->unsignedBigInteger('perencanaan_id');
            $table->unsignedBigInteger('pegawai_id');

            // Detail Perjalanan
            $table->string('provinsi_tujuan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('jumlah_malam');

            // Golongan dan Tarif
            $table->string('golongan'); // eselon_i, eselon_ii, eselon_iii, eselon_iv
            $table->decimal('tarif_hotel_sbm', 15, 2);
            $table->decimal('persen_klaim', 5, 2)->default(30);
            $table->decimal('nominal_per_malam', 15, 2);
            $table->decimal('total_nominal', 15, 2);

            $table->timestamps();

            // Foreign Keys
            $table->foreign('perencanaan_id')->references('id')->on('perencanaans')->cascadeOnDelete();
            $table->foreign('pegawai_id')->references('id')->on('kepegawaians')->cascadeOnDelete();

            // Index
            $table->index('perencanaan_id');
            $table->index('pegawai_id');
            $table->index('provinsi_tujuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usulan_pembayarans');
    }
};
