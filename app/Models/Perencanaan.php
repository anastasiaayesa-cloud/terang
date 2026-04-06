<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\PerencanaanObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Perencanaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama_komponen',
        'dokumen_perencanaan_id',
        'usulan_id',
    ];

    protected static function booted(): void
    {
        static::observe(PerencanaanObserver::class);
    }

    public function dokumenPerencanaan()
    {
        return $this->belongsTo(DokumenPerencanaan::class, 'dokumen_perencanaan_id');
    }

    public function usulan()
    {
        return $this->belongsTo(Usulan::class);
    }

    public function daftarKegiatan(): MorphOne
    {
        return $this->morphOne(DaftarKegiatan::class, 'sumber');
    }
}
