<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usulan_pegawais', function (Blueprint $table) {
            $table->unsignedBigInteger('perencanaan_id')->nullable()->after('usulan_id');
            $table->foreign('perencanaan_id')->references('id')->on('perencanaans')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('usulan_pegawais', function (Blueprint $table) {
            $table->dropForeign(['perencanaan_id']);
            $table->dropColumn('perencanaan_id');
        });
    }
};
