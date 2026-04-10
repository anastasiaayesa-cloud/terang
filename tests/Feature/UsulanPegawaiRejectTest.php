<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Kepegawaian;
use App\Models\User;
use App\Models\Usulan;
use App\Models\UsulanPegawai;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsulanPegawaiRejectTest extends TestCase
{
    use RefreshDatabase;

    public function test_reject_sets_fields()
    {
        $user = User::factory()->create();
        // buat relasi terkait expected FK
        $kepegawaian = Kepegawaian::factory()->create();
        // buat Usulan manual untuk menghindari missing factory
        $usulan = Usulan::create([
            'nama_kegiatan' => 'Test Kegiatan',
            'tanggal_kegiatan' => now(),
            'lokasi_kegiatan' => 'Test Lokasi',
            'deskripsi' => 'Deskripsi',
            'status' => 'pending',
            'catatan' => '',
        ]);
        $proposal = UsulanPegawai::create([
            'usulan_id' => $usulan->id,
            'pegawai_id' => $kepegawaian->id,
            'status' => 'pending',
            'catatan' => '',
        ]);

        $proposal->reject(' tidak memenuhi syarat ', $user);

        $this->assertEquals('rejected', $proposal->refresh()->status);
        $this->assertEquals(' tidak memenuhi syarat ', $proposal->reject_reason);
        $this->assertEquals($user->id, $proposal->rejected_by);
        $this->assertNotNull($proposal->rejected_at);
    }
}
