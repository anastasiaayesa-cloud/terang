<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop pegawai_id if exists
        if (Schema::hasColumn('usulans', 'pegawai_id')) {
            Schema::table('usulans', function (Blueprint $table) {
                try {
                    $table->dropForeign(['pegawai_id']);
                } catch (Throwable $e) {
                    // Ignore if constraint name differs
                }
                $table->dropColumn('pegawai_id');
            });
        }

        // Drop status if exists
        if (Schema::hasColumn('usulans', 'status')) {
            Schema::table('usulans', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }

    public function down(): void
    {
        // Restore pegawai_id column if missing
        if (! Schema::hasColumn('usulans', 'pegawai_id')) {
            Schema::table('usulans', function (Blueprint $table) {
                $table->foreignId('pegawai_id')->nullable()->constrained('kepegawaians')->nullOnDelete();
            });
        }

        // Restore status column with original type
        if (! Schema::hasColumn('usulans', 'status')) {
            Schema::table('usulans', function (Blueprint $table) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            });
        }
    }
};
