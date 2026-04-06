<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\UsulanObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Usulan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'nama_kegiatan',
        'tanggal_kegiatan',
        'lokasi_kegiatan',
        'deskripsi',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
    ];

    protected static function booted(): void
    {
        static::observe(UsulanObserver::class);
    }

    public function kepegawaian()
    {
        return $this->belongsTo(Kepegawaian::class, 'pegawai_id', 'id');
    }

    public function perencanaans()
    {
        return $this->hasMany(Perencanaan::class);
    }

    public function daftarKegiatan(): MorphOne
    {
        return $this->morphOne(DaftarKegiatan::class, 'sumber');
    }
}
