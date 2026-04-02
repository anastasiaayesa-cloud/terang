<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function kepegawaian()
    {
        return $this->belongsTo(Kepegawaian::class, 'pegawai_id', 'id');
    }

    public function perencanaans()
    {
        return $this->hasMany(Perencanaan::class);
    }
}
