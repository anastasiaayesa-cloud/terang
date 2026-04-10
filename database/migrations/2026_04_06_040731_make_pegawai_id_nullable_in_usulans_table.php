<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usulans', function (Blueprint $table) {
            $table->dropForeign(['pegawai_id']);
            $table->unsignedBigInteger('pegawai_id')->nullable()->change();
            $table->foreign('pegawai_id')->references('id')->on('kepegawaians')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('usulans', function (Blueprint $table) {
            $table->dropForeign(['pegawai_id']);
            $table->unsignedBigInteger('pegawai_id')->nullable(false)->change();
            $table->foreign('pegawai_id')->references('id')->on('kepegawaians')->cascadeOnDelete();
        });
    }
};
