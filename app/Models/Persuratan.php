<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Persuratan extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'pegawai_id', // HAPUS/KOMENTARI INI: Karena hubungan pegawai sudah pindah ke tabel pivot
        'usulan_id',
        'perencanaan_id',
        'persuratan_kategori_id',
        'nama_surat',
        'file_pdf',
        'tanggal_upload',
        'perihal',
        'jenis_anggaran',
    ];

    /**
     * Relasi Many-to-Many ke Kepegawaian (Sistem Pivot)
     */
    public function pegawais(): BelongsToMany
    {
        // Pastikan tabel 'persuratan_pegawai' sudah ada di database
        return $this->belongsToMany(Kepegawaian::class, 'persuratan_pegawai', 'persuratan_id', 'pegawai_id');
    }

    public function perencanaan(): BelongsTo
    {
        return $this->belongsTo(Perencanaan::class);
    }

    public function usulan(): BelongsTo
    {
        return $this->belongsTo(Usulan::class, 'usulan_id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(PersuratanKategori::class, 'persuratan_kategori_id');
    }
}