<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daftar_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('sumber_type');
            $table->unsignedBigInteger('sumber_id');
            $table->string('nama_kegiatan');
            $table->string('tujuan_kegiatan')->nullable();
            $table->date('waktu_kegiatan')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status');
            $table->timestamps();

            $table->index(['sumber_type', 'sumber_id'], 'sumber_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daftar_kegiatans');
    }
};
