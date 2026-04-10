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
        'deskripsi_kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi_kegiatan',
        'file_laporan',
        'status',
        'catatan_reviewer',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function kepegawaian()
    {
        return $this->belongsTo(Kepegawaian::class, 'pegawai_id');
    }

    public function perencanaan()
    {
        return $this->belongsTo(Perencanaan::class);
    }
}
