<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DaftarActivities extends Model
{
    use HasFactory;

    protected $table = 'daftar_kegiatans';

    protected $fillable = [
        'sumber_type',
        'sumber_id',
        'nama_kegiatan',
        'nama_komponen',
        'tujuan_kegiatan',
        'waktu_kegiatan',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'waktu_kegiatan' => 'date',
    ];

    public function sumber(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Menangani label status.
     * Ditambahkan null coalescing (??) agar tidak error jika status kosong.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'usulan' => 'Usulan',
            'perencanaan' => 'Perencanaan',
            'tidak_ada_di_perencanaan' => 'Tidak ada di Perencanaan',
            // Jika status null atau tidak cocok, kembalikan string '-' atau 'Draft'
            default => $this->status ?? '-',
        };
    }

    /**
     * Menangani class CSS untuk badge status.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'usulan' => 'bg-blue-100 text-blue-800',
            'perencanaan' => 'bg-green-100 text-green-800',
            'tidak_ada_di_perencanaan' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
