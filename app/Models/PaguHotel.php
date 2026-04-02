<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaguHotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'provinsi',
        'eselon_i',
        'eselon_ii',
        'eselon_iii_gol_iv',
        'eselon_iv_gol_iii_ii_i',
        'tahun',
    ];

    protected $casts = [
        'eselon_i' => 'decimal:2',
        'eselon_ii' => 'decimal:2',
        'eselon_iii_gol_iv' => 'decimal:2',
        'eselon_iv_gol_iii_ii_i' => 'decimal:2',
        'tahun' => 'integer',
    ];

    public function getTarifByGolongan(string $golongan): float
    {
        return match ($golongan) {
            'eselon_i' => (float) $this->eselon_i,
            'eselon_ii' => (float) $this->eselon_ii,
            'eselon_iii' => (float) $this->eselon_iii_gol_iv,
            'eselon_iv' => (float) $this->eselon_iv_gol_iii_ii_i,
            default => 0,
        };
    }
}
