<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\DaftarKegiatan;
use App\Models\Usulan;

class UsulanObserver
{
    public function created(Usulan $usulan): void
    {
        $perencanaanExists = $this->checkPerencanaanExists($usulan);

        DaftarKegiatan::create([
            'sumber_type' => Usulan::class,
            'sumber_id' => $usulan->id,
            'nama_kegiatan' => $usulan->nama_kegiatan,
            'tujuan_kegiatan' => $usulan->lokasi_kegiatan,
            'waktu_kegiatan' => $usulan->tanggal_kegiatan,
            'keterangan' => $usulan->deskripsi,
            'status' => $perencanaanExists ? 'usulan' : 'tidak_ada_di_perencanaan',
        ]);
    }

    public function updated(Usulan $usulan): void
    {
        $daftarKegiatan = DaftarKegiatan::where('sumber_type', Usulan::class)
            ->where('sumber_id', $usulan->id)
            ->first();

        if ($daftarKegiatan) {
            $perencanaanExists = $this->checkPerencanaanExists($usulan);

            $daftarKegiatan->update([
                'nama_kegiatan' => $usulan->nama_kegiatan,
                'tujuan_kegiatan' => $usulan->lokasi_kegiatan,
                'waktu_kegiatan' => $usulan->tanggal_kegiatan,
                'keterangan' => $usulan->deskripsi,
                'status' => $perencanaanExists ? 'usulan' : 'tidak_ada_di_perencanaan',
            ]);
        }
    }

    public function deleted(Usulan $usulan): void
    {
        DaftarKegiatan::where('sumber_type', Usulan::class)
            ->where('sumber_id', $usulan->id)
            ->delete();
    }

    protected function checkPerencanaanExists(Usulan $usulan): bool
    {
        return $usulan->perencanaans()->exists();
    }
}
