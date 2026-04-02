<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing FK first
        Schema::table('perencanaans', function (Blueprint $table) {
            $table->dropForeign(['file_pdf']);
        });

        // Rename file_pdf → dokumen_perencanaan_id
        Schema::table('perencanaans', function (Blueprint $table) {
            $table->renameColumn('file_pdf', 'dokumen_perencanaan_id');
        });

        // Add usulan_id and re-add FK
        Schema::table('perencanaans', function (Blueprint $table) {
            $table->unsignedBigInteger('usulan_id')->nullable()->after('dokumen_perencanaan_id');

            $table->foreign('dokumen_perencanaan_id')
                ->references('id')
                ->on('dokumen_perencanaans')
                ->nullOnDelete();

            $table->foreign('usulan_id')
                ->references('id')
                ->on('usulans')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('perencanaans', function (Blueprint $table) {
            $table->dropForeign(['dokumen_perencanaan_id']);
            $table->dropForeign(['usulan_id']);
            $table->dropColumn('usulan_id');
            $table->renameColumn('dokumen_perencanaan_id', 'file_pdf');

            $table->foreign('file_pdf')
                ->references('id')
                ->on('dokumen_perencanaans')
                ->nullOnDelete();
        });
    }
};
