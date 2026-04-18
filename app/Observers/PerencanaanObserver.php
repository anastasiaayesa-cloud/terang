<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\DaftarActivities;
use App\Models\Perencanaan;
use App\Models\Usulan;

class PerencanaanObserver
{
    public function created(Perencanaan $perencanaan): void
    {
        $namaKegiatan = null;

        // Logika Pencarian Nama Kegiatan
        if ($perencanaan->usulan_id) {
            $usulan = Usulan::find($perencanaan->usulan_id);
            if ($usulan) {
                $namaKegiatan = $usulan->nama_kegiatan;
            }
        }

        // Logika Status Dinamis
        // Jika usulan_id ada, status 'usulan'. Jika tidak, status 'perencanaan'.
        $statusDinamis = $perencanaan->usulan_id ? 'usulan' : 'perencanaan';

        DaftarActivities::create([
            'sumber_type' => Perencanaan::class,
            'sumber_id' => $perencanaan->id,
            'nama_kegiatan' => $namaKegiatan,
            'nama_komponen' => $perencanaan->nama_komponen,
            'status' => $statusDinamis,
            'keterangan' => null, // Sesuai permintaan sebelumnya: dikosongkan
            'tujuan_kegiatan' => null,
            'waktu_kegiatan' => null,
        ]);
    }

    public function updated(Perencanaan $perencanaan): void
    {
        $daftarKegiatans = DaftarActivities::where('sumber_type', Perencanaan::class)
            ->where('sumber_id', $perencanaan->id)
            ->first();

        if ($daftarKegiatans) {
            $namaKegiatan = $perencanaan->usulan_id && $perencanaan->usulan
                ? $perencanaan->usulan->nama_kegiatan
                : null;

            $daftarKegiatans->update([
                'nama_kegiatan' => $namaKegiatan,
                'nama_komponen' => $perencanaan->nama_komponen,
                'keterangan' => null, // Diubah menjadi null saat update
                'status' => 'perencanaan',
            ]);
        }
    }

    public function deleted(Perencanaan $perencanaan): void
    {
        DaftarActivities::where('sumber_type', Perencanaan::class)
            ->where('sumber_id', $perencanaan->id)
            ->delete();
    }
}
