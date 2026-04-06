<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\DaftarKegiatan;
use App\Models\Perencanaan;
use App\Models\Usulan;
use Illuminate\Console\Command;

class DaftarKegiatanSync extends Command
{
    protected $signature = 'daftar-kegiatan:sync';

    protected $description = 'Sync daftar kegiatan from usulan and perencanaan';

    public function handle(): int
    {
        $this->info('Syncing daftar kegiatan...');

        DaftarKegiatan::truncate();

        $usulansCount = 0;
        $perencanaansCount = 0;

        $usulans = Usulan::all();
        foreach ($usulans as $usulan) {
            $hasPerencanaan = $usulan->perencanaans()->exists();

            DaftarKegiatan::create([
                'sumber_type' => Usulan::class,
                'sumber_id' => $usulan->id,
                'nama_kegiatan' => $usulan->nama_kegiatan,
                'tujuan_kegiatan' => $usulan->lokasi_kegiatan,
                'waktu_kegiatan' => $usulan->tanggal_kegiatan,
                'keterangan' => $usulan->deskripsi,
                'status' => $hasPerencanaan ? 'usulan' : 'tidak_ada_di_perencanaan',
            ]);

            $usulansCount++;
        }

        $perencanaans = Perencanaan::whereNull('usulan_id')->get();
        foreach ($perencanaans as $perencanaan) {
            DaftarKegiatan::create([
                'sumber_type' => Perencanaan::class,
                'sumber_id' => $perencanaan->id,
                'nama_kegiatan' => $perencanaan->nama_komponen,
                'tujuan_kegiatan' => null,
                'waktu_kegiatan' => null,
                'keterangan' => $perencanaan->kode,
                'status' => 'perencanaan',
            ]);

            $perencanaansCount++;
        }

        $this->info('Sync complete!');
        $this->line("Usulan: {$usulansCount} synced");
        $this->line("Perencanaan mandiri: {$perencanaansCount} synced");
        $this->line('Total: '.DaftarKegiatan::count().' entries');

        return Command::SUCCESS;
    }
}
