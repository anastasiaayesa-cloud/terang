<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kabupaten extends Model
{
    use HasFactory;

    protected $fillable = ['nama'];

    public function instansi()
    {
        return $this->hasMany(Instansi::class);
    }
}
