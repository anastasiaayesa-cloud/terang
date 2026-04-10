<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PengajuanPencairan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'perencanaan_id',
        'nomor_surat',
        'tanggal_pengajuan',
        'tanggal_cair',
        'status',
        'catatan_reviewer',
        'uang_harian_nominal',
        'jumlah_hari',
        'uang_harian_total',
        'total_nominal',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_cair' => 'date',
        'uang_harian_nominal' => 'decimal:2',
        'jumlah_hari' => 'integer',
        'uang_harian_total' => 'decimal:2',
        'total_nominal' => 'decimal:2',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Kepegawaian::class, 'pegawai_id');
    }

    public function perencanaan(): BelongsTo
    {
        return $this->belongsTo(Perencanaan::class);
    }

    public function buktiPengeluarans(): BelongsToMany
    {
        return $this->belongsToMany(BuktiPengeluaran::class, 'bukti_pengajuan', 'pengajuan_id', 'bukti_pengeluaran_id')
            ->withTimestamps();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'dicairkan' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'dicairkan' => 'Dicairkan',
            default => $this->status,
        };
    }
}
