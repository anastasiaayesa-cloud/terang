<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiPengeluaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'perencanaan_id',
        'pegawai_id',
        'tipe_bukti',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'keterangan',
        'nominal',
        'tanggal_bukti',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_bukti' => 'date',
        'file_size' => 'integer',
    ];

    public function perencanaan()
    {
        return $this->belongsTo(Perencanaan::class, 'perencanaan_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Kepegawaian::class, 'pegawai_id');
    }

    public function getTipeBuktiLabelAttribute(): string
    {
        return match ($this->tipe_bukti) {
            'tiket_pesawat' => 'Tiket Pesawat',
            'tiket_kapal' => 'Tiket Kapal',
            'tiket_kereta' => 'Tiket Kereta',
            'tiket_taxi' => 'Tiket Taxi',
            'tiket_hotel' => 'Tiket Hotel',
            'bukti_lainnya' => 'Bukti Lainnya',
            default => $this->tipe_bukti,
        };
    }

    public function getTipeBuktiLabelIconAttribute(): string
    {
        return match ($this->tipe_bukti) {
            'tiket_pesawat' => '✈️',
            'tiket_kapal' => '🚢',
            'tiket_kereta' => '🚆',
            'tiket_taxi' => '🚕',
            'tiket_hotel' => '🏨',
            'bukti_lainnya' => '📋',
            default => '📎',
        };
    }
}
