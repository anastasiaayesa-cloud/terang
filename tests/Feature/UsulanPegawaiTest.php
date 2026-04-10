<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\UsulanPegawais\UsulanPegawaisForm;
use App\Livewire\UsulanPegawais\UsulanPegawaisIndex;
use App\Models\Kepegawaian;
use App\Models\User;
use App\Models\Usulan;
use App\Models\UsulanPegawai;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UsulanPegawaiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('usulan-pegawais.index'));

        $response->assertOk();
    }

    public function test_index_shows_usulans_grouped(): void
    {
        $user = User::factory()->create();
        $usulan = Usulan::create([
            'nama_kegiatan' => 'Test Kegiatan',
            'tanggal_kegiatan' => now(),
            'lokasi_kegiatan' => 'Test Lokasi',
        ]);
        $pegawai = Kepegawaian::factory()->create();
        UsulanPegawai::create([
            'usulan_id' => $usulan->id,
            'pegawai_id' => $pegawai->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('usulan-pegawais.index'));

        $response->assertSee('Test Kegiatan');
        $response->assertSee($pegawai->nama);
    }

    public function test_index_shows_usulan_without_pegawai(): void
    {
        $user = User::factory()->create();
        Usulan::create([
            'nama_kegiatan' => 'Kegiatan Tanpa Pegawai',
            'tanggal_kegiatan' => now(),
            'lokasi_kegiatan' => 'Test Lokasi',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('usulan-pegawais.index'));

        $response->assertSee('Kegiatan Tanpa Pegawai');
        $response->assertSee('Belum ada pegawai yang diajukan');
    }

    public function test_create_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('usulan-pegawais.create'));

        $response->assertOk();
    }

    public function test_edit_page_is_displayed(): void
    {
        $user = User::factory()->create();
        $usulan = Usulan::create([
            'nama_kegiatan' => 'Test Kegiatan',
            'tanggal_kegiatan' => now(),
            'lokasi_kegiatan' => 'Test Lokasi',
        ]);
        $pegawai = Kepegawaian::factory()->create();
        $usulanPegawai = UsulanPegawai::create([
            'usulan_id' => $usulan->id,
            'pegawai_id' => $pegawai->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('usulan-pegawais.edit', $usulanPegawai));

        $response->assertOk();
    }

    public function test_can_create_usulan_pegawai_single(): void
    {
        $user = User::factory()->create();
        $usulan = Usulan::create([
            'nama_kegiatan' => 'Test Kegiatan',
            'tanggal_kegiatan' => now(),
            'lokasi_kegiatan' => 'Test Lokasi',
        ]);
        $pegawai = Kepegawaian::factory()->create();

        Livewire::actingAs($user)
            ->test(UsulanPegawaisForm::class, ['usulan_id' => $usulan->id])
            ->set('pegawai_ids', [$pegawai->id])
            ->set('catatan', 'Test catatan')
            ->call('submit')
            ->assertRedirect(route('usulan-pegawais.index'));

        $this->assertDatabaseHas('usulan_pegawais', [
            'usulan_id' => $usulan->id,
            'pegawai_id' => $pegawai->id,
            'catatan' => 'Test catatan',
        ]);
    }

    public function test_can_create_usulan_pegawai_multiple(): void
    {
        $user = User::factory()->create();
        $usulan = Usulan::create([
            'nama_kegiatan' => 'Test Kegiatan',
            'tanggal_kegiatan' => now(),
            'lokasi_kegiatan' => 'Test Lokasi',
        ]);
        $pegawai1 = Kepegawaian::factory()->create(['nama' => 'Pegawai A']);
        $pegawai2 = Kepegawaian::factory()->create(['nama' => 'Pegawai B']);

        Livewire::actingAs($user)
            ->test(UsulanPegawaisForm::class, ['usulan_id' => $usulan->id])
            ->set('pegawai_ids', [$pegawai1->id, $pegawai2->id])
            ->set('catatan', 'Catatan bersama')
            ->call('submit')
            ->assertRedirect(route('usulan-pegawais.index'));

        $this->assertDatabaseHas('usulan_pegawais', [
            'usulan_id' => $usulan->id,
            'pegawai_id' => $pegawai1->id,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('usulan_pegawais', [
            'usulan_id' => $usulan->id,
            'pegawai_id' => $pegawai2->id,
            'status' => 'pending',
        ]);
    }

    public function test_can_update_usulan_pegawai(): void
    {
        $user = User::factory()->create();
        $usulan = Usulan::create([
            'nama_kegiatan' => 'Test Kegiatan',
            'tanggal_kegiatan' => now(),
            'lokasi_kegiatan' => 'Test Lokasi',
        ]);
        $pegawai = Kepegawaian::factory()->create();
        $usulanPegawai = UsulanPegawai::create([
            'usulan_id' => $usulan->id,
            'pegawai_id' => $pegawai->id,
            'catatan' => 'Catatan lama',
        ]);

        Livewire::actingAs($user)
            ->test(UsulanPegawaisForm::class, ['usulanPegawai' => $usulanPegawai->id])
            ->set('pegawai_ids', [$pegawai->id])
            ->set('catatan', 'Catatan baru')
            ->call('submit')
            ->assertRedirect(route('usulan-pegawais.index'));

        $this->assertDatabaseHas('usulan_pegawais', [
            'id' => $usulanPegawai->id,
            'catatan' => 'Catatan baru',
        ]);
    }

    public function test_can_approve_usulan_pegawai(): void
    {
        $user = User::factory()->create();
        $usulan = Usulan::create([
            'nama_kegiatan' => 'Test Kegiatan',
            'tanggal_kegiatan' => now(),
            'lokasi_kegiatan' => 'Test Lokasi',
        ]);
        $pegawai = Kepegawaian::factory()->create();
        $usulanPegawai = UsulanPegawai::create([
            'usulan_id' => $usulan->id,
            'pegawai_id' => $pegawai->id,
            'status' => 'pending',
        ]);

        Livewire::actingAs($user)
            ->test(UsulanPegawaisIndex::class)
            ->call('approve', $usulanPegawai->id);

        $this->assertDatabaseHas('usulan_pegawais', [
            'id' => $usulanPegawai->id,
            'status' => 'approved',
        ]);
    }

    public function test_can_reject_usulan_pegawai(): void
    {
        $user = User::factory()->create();
        $usulan = Usulan::create([
            'nama_kegiatan' => 'Test Kegiatan',
            'tanggal_kegiatan' => now(),
            'lokasi_kegiatan' => 'Test Lokasi',
        ]);
        $pegawai = Kepegawaian::factory()->create();
        $usulanPegawai = UsulanPegawai::create([
            'usulan_id' => $usulan->id,
            'pegawai_id' => $pegawai->id,
            'status' => 'pending',
        ]);

        Livewire::actingAs($user)
            ->test(UsulanPegawaisIndex::class)
            ->call('reject', $usulanPegawai->id);

        $this->assertDatabaseHas('usulan_pegawais', [
            'id' => $usulanPegawai->id,
            'status' => 'rejected',
        ]);
    }

    public function test_can_delete_usulan_pegawai(): void
    {
        $user = User::factory()->create();
        $usulan = Usulan::create([
            'nama_kegiatan' => 'Test Kegiatan',
            'tanggal_kegiatan' => now(),
            'lokasi_kegiatan' => 'Test Lokasi',
        ]);
        $pegawai = Kepegawaian::factory()->create();
        $usulanPegawai = UsulanPegawai::create([
            'usulan_id' => $usulan->id,
            'pegawai_id' => $pegawai->id,
            'status' => 'pending',
        ]);

        Livewire::actingAs($user)
            ->test(UsulanPegawaisIndex::class)
            ->call('delete', $usulanPegawai->id);

        $this->assertDatabaseMissing('usulan_pegawais', [
            'id' => $usulanPegawai->id,
        ]);
    }

    public function test_requires_valid_usulan_id(): void
    {
        $user = User::factory()->create();
        $pegawai = Kepegawaian::factory()->create();

        Livewire::actingAs($user)
            ->test(UsulanPegawaisForm::class)
            ->set('usulan_id', 999)
            ->set('pegawai_ids', [$pegawai->id])
            ->call('submit')
            ->assertHasErrors(['usulan_id']);
    }

    public function test_requires_valid_pegawai_id(): void
    {
        $user = User::factory()->create();
        $usulan = Usulan::create([
            'nama_kegiatan' => 'Test Kegiatan',
            'tanggal_kegiatan' => now(),
            'lokasi_kegiatan' => 'Test Lokasi',
        ]);

        Livewire::actingAs($user)
            ->test(UsulanPegawaisForm::class)
            ->set('pegawai_ids', [999])
            ->call('submit')
            ->assertHasErrors(['pegawai_ids.0']);
    }

    public function test_requires_pegawai_ids(): void
    {
        $user = User::factory()->create();
        $usulan = Usulan::create([
            'nama_kegiatan' => 'Test Kegiatan',
            'tanggal_kegiatan' => now(),
            'lokasi_kegiatan' => 'Test Lokasi',
            'status' => 'pending',
        ]);

        Livewire::actingAs($user)
            ->test(UsulanPegawaisForm::class)
            ->set('pegawai_ids', [])
            ->call('submit')
            ->assertHasErrors(['pegawai_ids']);
    }
}
