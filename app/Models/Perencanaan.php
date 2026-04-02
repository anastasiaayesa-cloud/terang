<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perencanaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama_komponen',
        'dokumen_perencanaan_id',
        'usulan_id',
    ];

    public function dokumenPerencanaan()
    {
        return $this->belongsTo(DokumenPerencanaan::class, 'dokumen_perencanaan_id');
    }

    public function usulan()
    {
        return $this->belongsTo(Usulan::class);
    }
}
