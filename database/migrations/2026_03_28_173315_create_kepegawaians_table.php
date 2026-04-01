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
        Schema::create('kepegawaians', function (Blueprint $table) {
    $table->bigIncrements('id');

    $table->string('nama')->nullable();
    $table->string('nip')->nullable()->unique();
    $table->string('jabatan')->nullable();

    $table->unsignedBigInteger('pangkat_id')->nullable();
    $table->string('tempat_lahir')->nullable();
    $table->date('tgl_lahir')->nullable();

    $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
    $table->enum('agama', ['Islam', 'Kristen Katolik', 'Kristen Protestan', 'Hindu', 'Buddha', 'Konghucu'])->nullable();

    $table->unsignedBigInteger('instansi_id')->nullable();
    $table->string('hp')->nullable();

    $table->string('email')->unique();
    $table->string('npwp')->nullable();

    $table->unsignedBigInteger('bank_id')->nullable();
    $table->string('no_rek')->nullable();

    $table->unsignedBigInteger('pendidikan_id')->nullable();
    $table->boolean('is_bpmp')->default(false);

    $table->unsignedBigInteger('user_id')->nullable();

    $table->timestamps();

    // Foreign Keys
    $table->foreign('pangkat_id')->references('id')->on('pangkats')->nullOnDelete();
    $table->foreign('instansi_id')->references('id')->on('instansis')->nullOnDelete();
    $table->foreign('bank_id')->references('id')->on('banks')->nullOnDelete();
    $table->foreign('pendidikan_id')->references('id')->on('pendidikans')->nullOnDelete();
    $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kepegawaians');
    }
};
