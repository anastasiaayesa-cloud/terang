<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\UsulanObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Usulan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kegiatan',
        'tanggal_kegiatan',
        'lokasi_kegiatan',
        'deskripsi',
        'catatan',
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
    ];

    protected static function booted(): void
    {
        static::observe(UsulanObserver::class);
    }

    // Kolom pegawai_id telah dihapus; relasi kepegawaian telah dihapus demi konsistensi skema baru

    public function perencanaans()
    {
        return $this->hasMany(Perencanaan::class);
    }

    public function daftarKegiatan(): MorphOne
    {
        return $this->morphOne(DaftarKegiatan::class, 'sumber');
    }

    public function usulanPegawais()
    {
        return $this->hasMany(UsulanPegawai::class, 'usulan_id');
    }

    // Scope: Usulan yang memiliki pegawai dengan status approved
    public function scopeWithApprovedPegawai(Builder $query): Builder
    {
        return $query->whereHas('usulanPegawais', function (Builder $q) {
            $q->where('status', 'approved');
        })->distinct();
    }

    public function persuratans()
    {
        return $this->hasMany(Persuratan::class, 'usulan_id');
    }
}
