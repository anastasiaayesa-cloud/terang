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
            $table->unsignedBigInteger('file_pdf')->nullable();
            $table->timestamps();

            $table->foreign('file_pdf')->references('id')->on('dokumen_perencanaans')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perencanaans');
    }
};
