<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'perencanaan_id',
        'judul_laporan',
        'usulan_id',
        'deskripsi_kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi_kegiatan',
        'file_laporan',
        'status',
        'catatan_reviewer',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function kepegawaian()
    {
        return $this->belongsTo(Kepegawaian::class, 'pegawai_id');
    }

    public function perencanaan()
    {
        return $this->belongsTo(Perencanaan::class, 'perencanaan_id');
    }

    public function usulan(): BelongsTo
    {
        return $this->belongsTo(Usulan::class, 'usulan_id');
    }
}
