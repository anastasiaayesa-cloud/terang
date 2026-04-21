<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsulanPegawai extends Model
{
    use HasFactory;

    protected $fillable = [
        'usulan_id',
        'perencanaan_id', // Tambahkan baris ini
        'pegawai_id',
        'status',
        'catatan',
        'reject_reason',
        'rejected_by',
        'rejected_at',
    ];

    public function usulan()
    {
        // Kita arahkan langsung ke model Usulan menggunakan usulan_id
        return $this->belongsTo(Usulan::class, 'usulan_id');
    }

    public function kepegawaian(): BelongsTo
    {
        return $this->belongsTo(Kepegawaian::class, 'pegawai_id');
    }

    // Penanggung jawab penolakan (reject) jika diterapkan
    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    // Aksi reject dengan menyimpan alasan dan metadata
    public function reject(string $reason, ?User $by = null): void
    {
        $this->status = 'rejected';
        $this->reject_reason = $reason;
        // Jangan simpan rejected_by untuk sekarang sesuai permintaan
        $this->rejected_at = now();
        $this->save();
    }

    public function persuratan()
    {
        return $this->hasOne(Persuratan::class, 'pegawai_id', 'pegawai_id')
            ->whereColumn('usulan_id', 'usulan_id');
    }

    public function perencanaan()
    {
        // Pastikan foreign key-nya benar, misal 'perencanaan_id'
        return $this->belongsTo(Perencanaan::class, 'perencanaan_id'); 
    }
}
