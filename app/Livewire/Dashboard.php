<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Keuangan;
use App\Models\UsulanPegawai;
use Livewire\Component;

class Dashboard extends Component
{
    public $anggarans = [];

    public $totalKeseluruhan = 0;

    public function render()
    {
        // 1. Ambil data anggaran dari Keuangan (uang dibayarkan)
        $anggaransCollection = Keuangan::whereNotNull('uang_dibayarkan')
            ->with('usulan')
            ->get()
            ->groupBy(function ($keuangan) {
                $tanggal = $keuangan->usulan?->tanggal_kegiatan;
                return $tanggal ? \Carbon\Carbon::parse($tanggal)->year : 'Tanpa Tahun';
            })
            ->map(function ($group, $key) {
                return [
                    'tahun' => $key,
                    'total' => $group->sum('uang_dibayarkan'),
                    'jumlah_kegiatan' => $group->pluck('usulan_id')
                        ->filter()
                        ->unique()
                        ->count(),
                ];
            });

        // 2. Ambil data petugas dari UsulanPegawai (Total Peserta)
        $petugasData = UsulanPegawai::where('status', 'approved')
            ->with('usulan')
            ->get()
            ->groupBy(function ($up) {
                $tanggal = $up->usulan?->tanggal_kegiatan;
                return $tanggal ? \Carbon\Carbon::parse($tanggal)->year : 'Tanpa Tahun';
            })
            ->mapWithKeys(function ($group, $key) {
                return [$key => $group->count()];
            })
            ->toArray();

        // 3a. Ambil data petugas yang SUDAH dibayarkan (selesai_at NOT NULL)
        $petugasSelesai = Keuangan::whereNotNull('selesai_at')
            ->with('usulan')
            ->get()
            ->groupBy(function ($keuangan) {
                $tanggal = $keuangan->usulan?->tanggal_kegiatan;
                return $tanggal ? \Carbon\Carbon::parse($tanggal)->year : 'Tanpa Tahun';
            })
            ->mapWithKeys(function ($group, $key) {
                $jumlahUnik = $group->map(function ($keuangan) {
                    return $keuangan->usulan_id . '-' . $keuangan->pegawai_id;
                })->unique()->count();

                return [$key => $jumlahUnik];
            })
            ->toArray();

        // 3b. PERBAIKAN: Ambil data petugas yang BELUM dibayarkan penuh 
        // (Mencari yang statusnya BUKAN 'full')
        $petugasBelumSelesai = Keuangan::where(function($query) {
            $query->where('status', '!=', 'full')
                  ->orWhereNull('status');
        })
        ->with('usulan')
        ->get()
        ->groupBy(function ($keuangan) {
            $tanggal = $keuangan->usulan?->tanggal_kegiatan;
            return $tanggal ? \Carbon\Carbon::parse($tanggal)->year : 'Tanpa Tahun';
        })
        ->mapWithKeys(function ($group, $key) {
            // Tetap gunakan kombinasi unik usulan_id dan pegawai_id
            $jumlahUnikBelum = $group->map(function ($keuangan) {
                return $keuangan->usulan_id . '-' . $keuangan->pegawai_id;
            })->unique()->count();

            return [$key => $jumlahUnikBelum];
        })
        ->toArray();

        // 4. Gabungkan semua data ke anggaran menggunakan .map()
        $anggaransTerisi = $anggaransCollection->map(function ($item) use ($petugasData, $petugasSelesai, $petugasBelumSelesai) {
            $tahun = $item['tahun'];
            $item['jumlah_petugas'] = $petugasData[$tahun] ?? 0;
            $item['jumlah_petugas_dibayarkan'] = $petugasSelesai[$tahun] ?? 0;
            $item['jumlah_petugas_belum_dibayarkan'] = $petugasBelumSelesai[$tahun] ?? 0; // Masukkan ke array
            return $item;
        });

        // 5. Ubah ke bentuk Array murni sebelum dikirim ke properti Livewire
        $this->anggarans = $anggaransTerisi->sortByDesc('tahun')->values()->toArray();
        $this->totalKeseluruhan = (float) $anggaransTerisi->sum('total');

        return view('livewire.dashboard');
    }
}