<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanPembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'perencanaan_id',
        'pegawai_id',
        'provinsi_tujuan',
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_malam',
        'golongan',
        'tarif_hotel_sbm',
        'persen_klaim',
        'nominal_per_malam',
        'total_nominal',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'jumlah_malam' => 'integer',
        'tarif_hotel_sbm' => 'decimal:2',
        'persen_klaim' => 'decimal:2',
        'nominal_per_malam' => 'decimal:2',
        'total_nominal' => 'decimal:2',
    ];

    public function perencanaan()
    {
        return $this->belongsTo(Perencanaan::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Kepegawaian::class, 'pegawai_id');
    }

    public function getGolonganLabelAttribute(): string
    {
        return match ($this->golongan) {
            'eselon_i' => 'Eselon I',
            'eselon_ii' => 'Eselon II',
            'eselon_iii' => 'Eselon III / Gol IV',
            'eselon_iv' => 'Eselon IV / Gol III, II, I',
            default => $this->golongan,
        };
    }

    public static function hitungGolonganDariPangkat(int $pangkatId): string
    {
        // Eselon I: IV/c, IV/d, IV/e (ID: 11, 12, 13)
        if (in_array($pangkatId, [11, 12, 13])) {
            return 'eselon_i';
        }

        // Eselon II: IV/a, IV/b (ID: 9, 10)
        if (in_array($pangkatId, [9, 10])) {
            return 'eselon_ii';
        }

        // Eselon III: III/c, III/d (ID: 7, 8)
        if (in_array($pangkatId, [7, 8])) {
            return 'eselon_iii';
        }

        // Eselon IV: I/a s/d III/b (ID: 1-6)
        return 'eselon_iv';
    }
}
