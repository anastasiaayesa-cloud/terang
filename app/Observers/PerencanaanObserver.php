<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\DaftarKegiatan;
use App\Models\Perencanaan;
use App\Models\Usulan;

class PerencanaanObserver
{
    public function created(Perencanaan $perencanaan): void
    {
        if ($perencanaan->usulan_id) {
            $this->syncUsulanStatus($perencanaan->usulan_id);
        } else {
            DaftarKegiatan::create([
                'sumber_type' => Perencanaan::class,
                'sumber_id' => $perencanaan->id,
                'nama_kegiatan' => $perencanaan->nama_komponen,
                'tujuan_kegiatan' => null,
                'waktu_kegiatan' => null,
                'keterangan' => $perencanaan->kode,
                'status' => 'perencanaan',
            ]);
        }
    }

    public function updated(Perencanaan $perencanaan): void
    {
        $oldUsulanId = $perencanaan->getOriginal('usulan_id');
        $newUsulanId = $perencanaan->usulan_id;

        if ($oldUsulanId === $newUsulanId) {
            if ($newUsulanId === null) {
                $daftarKegiatan = DaftarKegiatan::where('sumber_type', Perencanaan::class)
                    ->where('sumber_id', $perencanaan->id)
                    ->first();

                if ($daftarKegiatan) {
                    $daftarKegiatan->update([
                        'nama_kegiatan' => $perencanaan->nama_komponen,
                        'keterangan' => $perencanaan->kode,
                    ]);
                }
            }

            return;
        }

        if ($oldUsulanId !== null) {
            $this->syncUsulanStatus($oldUsulanId);
        }

        if ($newUsulanId !== null) {
            $daftarKegiatan = DaftarKegiatan::where('sumber_type', Perencanaan::class)
                ->where('sumber_id', $perencanaan->id)
                ->first();
            if ($daftarKegiatan) {
                $daftarKegiatan->delete();
            }
            $this->syncUsulanStatus($newUsulanId);
        } else {
            DaftarKegiatan::create([
                'sumber_type' => Perencanaan::class,
                'sumber_id' => $perencanaan->id,
                'nama_kegiatan' => $perencanaan->nama_komponen,
                'tujuan_kegiatan' => null,
                'waktu_kegiatan' => null,
                'keterangan' => $perencanaan->kode,
                'status' => 'perencanaan',
            ]);
        }
    }

    public function deleted(Perencanaan $perencanaan): void
    {
        if ($perencanaan->usulan_id) {
            $this->syncUsulanStatus($perencanaan->usulan_id);
        } else {
            DaftarKegiatan::where('sumber_type', Perencanaan::class)
                ->where('sumber_id', $perencanaan->id)
                ->delete();
        }
    }

    protected function syncUsulanStatus(int $usulanId): void
    {
        $usulan = Usulan::find($usulanId);
        if (! $usulan) {
            return;
        }

        $hasPerencanaan = $usulan->perencanaans()->exists();

        $daftarKegiatan = DaftarKegiatan::where('sumber_type', Usulan::class)
            ->where('sumber_id', $usulanId)
            ->first();

        if ($daftarKegiatan) {
            $daftarKegiatan->update([
                'status' => $hasPerencanaan ? 'usulan' : 'tidak_ada_di_perencanaan',
            ]);
        }
    }
}
