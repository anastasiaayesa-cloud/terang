<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kepegawaian extends Model
{
    use HasFactory;

    protected $table = 'kepegawaians';

    // Primary key custom
    protected $primaryKey = 'pegawai_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'nama',
        'nip',
        'jabatan',
        'pangkat_id',
        'tempat_lahir',
        'tgl_lahir',
        'jenis_kelamin',
        'agama',
        'instansi_id',
        'hp',
        'email',
        'npwp',
        'bank_id',
        'no_rek',
        'pendidikan_id',
        'is_bpmp',
        'user_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTING (BIAR RAPI & AMAN)
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'is_bpmp' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    //pangkat_id → pangkats.id
    public function pangkat()
    {
        return $this->belongsTo(Pangkat::class);
    }

    // // instansi_id → instansis.id
    // public function instansi()
    // {
    //     return $this->belongsTo(Instansi::class, 'instansi_id');
    // }

    // bank_id → banks.id
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    // pendidikan_id → pendidikans.id
    public function pendidikan()
    {
        return $this->belongsTo(Pendidikan::class);
    }

    


    // // user_id → users.id
    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }

    public function usulans()
    {
        return $this->hasMany(Usulan::class, 'pegawai_id');
    }

    // /*
    // |--------------------------------------------------------------------------
    // | ACCESSOR (BIAR ENAK DI BLADE)
    // |--------------------------------------------------------------------------
    // */

    // public function getIsBpmpTextAttribute()
    // {
    //     return $this->is_bpmp ? 'Ya' : 'Tidak';
    // }
}
