<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DaftarKegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'sumber_type',
        'sumber_id',
        'nama_kegiatan',
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

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'usulan' => 'Usulan',
            'perencanaan' => 'Perencanaan',
            'tidak_ada_di_perencanaan' => 'Tidak ada di Perencanaan',
            default => $this->status,
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'usulan' => 'bg-blue-100 text-blue-800',
            'perencanaan' => 'bg-green-100 text-green-800',
            'tidak_ada_di_perencanaan' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function scopeDariUsulan($query)
    {
        return $query->where('sumber_type', Usulan::class);
    }

    public function scopeDariPerencanaan($query)
    {
        return $query->where('sumber_type', Perencanaan::class);
    }

    public function scopeStatusUsulan($query)
    {
        return $query->where('status', 'usulan');
    }

    public function scopeStatusTidakAdaDiPerencanaan($query)
    {
        return $query->where('status', 'tidak_ada_di_perencanaan');
    }
}
