<?php

declare(strict_types=1);

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
        Schema::create('pengajuan_pencairans', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pegawai_id');
            $table->unsignedBigInteger('perencanaan_id');
            $table->string('nomor_surat')->nullable();
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_cair')->nullable();
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'dicairkan',
            ])->default('pending');
            $table->text('catatan_reviewer')->nullable();
            $table->decimal('uang_harian_nominal', 15, 2)->default(0);
            $table->integer('jumlah_hari')->default(0);
            $table->decimal('uang_harian_total', 15, 2)->default(0);
            $table->decimal('total_nominal', 15, 2)->default(0);

            $table->timestamps();

            $table->foreign('pegawai_id')->references('id')->on('kepegawaians')->restrictOnDelete();
            $table->foreign('perencanaan_id')->references('id')->on('perencanaans')->restrictOnDelete();

            $table->index('pegawai_id');
            $table->index('perencanaan_id');
            $table->index('status');
            $table->index('tanggal_pengajuan');
        });

        Schema::create('bukti_pengajuan', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pengajuan_id');
            $table->unsignedBigInteger('bukti_pengeluaran_id');

            $table->timestamps();

            $table->foreign('pengajuan_id')
                ->references('id')
                ->on('pengajuan_pencairans')
                ->cascadeOnDelete();
            $table->foreign('bukti_pengeluaran_id')
                ->references('id')
                ->on('bukti_pengeluarans')
                ->restrictOnDelete();

            $table->unique(['pengajuan_id', 'bukti_pengeluaran_id']);

            $table->index('pengajuan_id');
            $table->index('bukti_pengeluaran_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukti_pengajuan');
        Schema::dropIfExists('pengajuan_pencairans');
    }
};
