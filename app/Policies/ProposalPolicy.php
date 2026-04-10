<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\UsulanPegawai;
use Illuminate\Auth\Access\Response;

class ProposalPolicy
{
    public function delete(User $user, UsulanPegawai $proposal): Response
    {
        // Guard: hentikan segala upaya penghapusan untuk saat ini
        return Response::deny('Penghapusan usulan pegawai tidak diizinkan.');
    }
}
