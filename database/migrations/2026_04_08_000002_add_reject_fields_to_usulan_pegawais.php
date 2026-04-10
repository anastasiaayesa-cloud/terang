<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usulan_pegawais', function (Blueprint $table) {
            $table->text('reject_reason')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->foreign('rejected_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('usulan_pegawais', function (Blueprint $table) {
            $table->dropForeign(['rejected_by']);
            $table->dropColumn(['reject_reason', 'rejected_by', 'rejected_at']);
        });
    }
};
