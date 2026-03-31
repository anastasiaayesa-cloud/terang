<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenPerencanaan extends Model
{
    protected $fillable = [
        'nama',
        'file_pdf',
        'tanggal',
    ];
}