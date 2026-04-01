<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DokumenPerencanaan extends Model
{
    protected $fillable = [
        'nama',
        'file_pdf',
        'tanggal',
    ];
}