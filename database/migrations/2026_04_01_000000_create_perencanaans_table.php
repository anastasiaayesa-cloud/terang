<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perencanaans', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->nullable();
            $table->string('nama_komponen');
            $table->unsignedBigInteger('dokumen_perencanaan_id')->nullable();
            $table->timestamps();

            $table->foreign('dokumen_perencanaan_id')->references('id')->on('dokumen_perencanaans')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perencanaans');
    }
};
