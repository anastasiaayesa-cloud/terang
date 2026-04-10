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
        Schema::create('usulan_pegawais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usulan_id');
            $table->unsignedBigInteger('pegawai_id');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('usulan_id')->references('id')->on('usulans')->cascadeOnDelete();
            $table->foreign('pegawai_id')->references('id')->on('kepegawaians')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usulan_pegawais');
    }
};
