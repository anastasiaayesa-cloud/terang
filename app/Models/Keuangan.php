<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    // app/Models/Keuangan.php

    protected $fillable = [
        'usulan_id',
        'pegawai_id',
        'perincian_bayar',
        'nominal',
        'jumlah',
        'satuan',
        'total',
        'jenis',
        'status',
        'alasan_penolakan',
        'uang_dibayarkan',
        'bukti_pengeluaran_id',
        'tanggal_kwitansi',
        'maksud_perjalanan_dinas',
        'alat_angkut',
        'tempat_berangkat',
        'tempat_tujuan',
        'selesai_at',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'total' => 'decimal:2',
        'jumlah' => 'integer',
        'selesai_at' => 'datetime',
    ];

    public function usulan()
    {
        // Setiap satu baris keuangan merujuk pada satu kegiatan
        return $this->belongsTo(Usulan::class, 'usulan_id');
    }

    public function kepegawaian()
    {
        // Setiap satu baris keuangan merujuk pada satu pegawai
        return $this->belongsTo(Kepegawaian::class, 'pegawai_id');
    }
}
