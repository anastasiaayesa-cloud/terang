<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\UsulanPegawai;
use App\Policies\ProposalPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProposalPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_denied_for_non_owner_without_admin()
    {
        $owner = User::factory()->create();
        $proposal = new UsulanPegawai(['pegawai_id' => 0, 'usulan_id' => 0]);
        // buat chain relasi palsu: Usulan -> Kepegawaian -> user_id
        $usulan = new class {};
        $kepegawaian = new class {};
        $kepegawaian->user_id = $owner->id;
        $usulan->kepegawaian = $kepegawaian;
        $proposal->setRelation('usulan', $usulan);
        $user = User::factory()->create();

        $policy = new ProposalPolicy;
        $response = $policy->delete($user, $proposal);

        $this->assertFalse($response->allowed());
        $this->assertSame('Anda tidak memiliki hak untuk menghapus usulan ini.', $response->message());
    }

    public function test_delete_allowed_for_owner()
    {
        $owner = User::factory()->create();
        $proposal = new UsulanPegawai(['pegawai_id' => 0, 'usulan_id' => 0]);
        $usulan = new class {};
        $kepegawaian = new class {};
        $kepegawaian->user_id = $owner->id;
        $usulan->kepegawaian = $kepegawaian;
        $proposal->setRelation('usulan', $usulan);

        $policy = new ProposalPolicy;
        $response = $policy->delete($owner, $proposal);
        $this->assertTrue($response->allowed());
    }
}
