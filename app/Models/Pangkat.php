<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Kepegawaian;

class Pangkat extends Model
{
    use HasFactory;

    protected $fillable = ['nama'];

    public function kepegawaian()
    {
        return $this->hasMany(kepegawaian::class);
    }
}
