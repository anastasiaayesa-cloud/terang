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
        Schema::create('pagu_hotels', function (Blueprint $table) {
            $table->id();
            $table->string('provinsi')->unique();
            $table->decimal('eselon_i', 15, 2);
            $table->decimal('eselon_ii', 15, 2);
            $table->decimal('eselon_iii_gol_iv', 15, 2);
            $table->decimal('eselon_iv_gol_iii_ii_i', 15, 2);
            $table->integer('tahun')->default(2025);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagu_hotels');
    }
};
