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
    ];

    // Pastikan casts hanya untuk angka
    protected $casts = [
        'nominal' => 'decimal:2',
        'total' => 'decimal:2',
        'jumlah' => 'integer',
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
