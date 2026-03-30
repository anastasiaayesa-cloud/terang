<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'alamat',
        'telp',
        'kabupaten_id'
    ];

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class);
    }
}
