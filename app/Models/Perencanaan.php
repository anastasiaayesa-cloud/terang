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
        'file_pdf',
    ];

    public function dokumenPerencanaan()
    {
        return $this->belongsTo(DokumenPerencanaan::class, 'file_pdf');
    }
}
