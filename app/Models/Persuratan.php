<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Persuratan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'nama_surat',
        'file_pdf',
        'tanggal_upload',
        'perihal',
        'jenis_anggaran',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Kepegawaian::class, 'pegawai_id');
    }
}
