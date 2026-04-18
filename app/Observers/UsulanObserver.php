<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Usulan;

class UsulanObserver
{
    public function updated(Usulan $usulan): void {}
}
