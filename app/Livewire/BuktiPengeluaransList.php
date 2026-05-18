<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\BuktiPengeluaran;
use App\Models\Perencanaan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class BuktiPengeluaransList extends Component
{
    public $groupedData = [];

    public $grandTotal = 0;

    public $hasKepegawaian = false;

    public $isSuperAdmin = false;

    public function mount()
    {
        $this->loadData();
    }

    public function isSuperAdmin(): bool
    {
        return Auth::user()?->hasRole('Super Admin') ?? false;
    }

    public function getCurrentKepegawaianId(): ?int
    {
        $kepegawaianId = Auth::user()?->kepegawaian?->id;
        $this->hasKepegawaian = $kepegawaianId !== null;
        $this->isSuperAdmin = $this->isSuperAdmin();

        return $kepegawaianId;
    }

    public function loadData()
    {
        $kepegawaianId = $this->getCurrentKepegawaianId();

        $query = Perencanaan::query();

        // 1. Saring Perencanaan berdasarkan 'pegawai_id' di tabel bukti_pengeluarans
        if (! $this->isSuperAdmin && $kepegawaianId) {
            $query->whereHas('buktiPengeluarans', function ($q) use ($kepegawaianId) {
                $q->where('pegawai_id', $kepegawaianId); 
            });
        }

        $perencanaans = $query->with([
            'usulan.usulanPegawais' => function ($query) use ($kepegawaianId) {
                $query->where('status', 'approved')->with('kepegawaian');

                if (! $this->isSuperAdmin && $kepegawaianId) {
                    // Sesuaikan jika tabel usulan_pegawais juga menggunakan pegawai_id atau kepegawaian_id
                    $query->where('pegawai_id', $kepegawaianId);
                }
            },
            // 2. Saring list bukti pengeluaran yang muncul agar hanya milik pegawai ini
            'buktiPengeluarans' => function ($query) use ($kepegawaianId) {
                if (! $this->isSuperAdmin && $kepegawaianId) {
                    $query->where('pegawai_id', $kepegawaianId);
                }
            },
            // 3. Relasi ke tabel kepegawaian (tetap menggunakan 'id' karena ini mengarah ke tabel kepegawaians)
            'buktiPengeluarans.kepegawaian' => function ($query) use ($kepegawaianId) {
                if (! $this->isSuperAdmin && $kepegawaianId) {
                    $query->where('id', $kepegawaianId);
                }
            },
        ])->get();

        $this->groupedData = $perencanaans->map(function ($perencanaan) {
            $buktiList = $perencanaan->buktiPengeluarans->sortByDesc('created_at');

            return [
                'perencanaan'  => $perencanaan,
                'buktiList'    => $buktiList,
                'totalNominal' => $buktiList->sum('nominal'),
                'fileCount'    => $buktiList->count(),
                'latestUpload' => $buktiList->first()?->created_at ?? $perencanaan->created_at,
            ];
        })->filter(function ($group) {
            return $group['fileCount'] > 0;
        })->sortByDesc('latestUpload')->values();

        $this->grandTotal = $this->groupedData->sum('totalNominal');
    }

    public function deleteBukti($buktiId)
    {
        $bukti = BuktiPengeluaran::findOrFail($buktiId);

        if (Storage::disk('public')->exists($bukti->file_path)) {
            Storage::disk('public')->delete($bukti->file_path);
        }

        $bukti->delete();

        session()->flash('success', 'Bukti pengeluaran berhasil dihapus.');

        $this->loadData();
    }

    public function render()
    {
        return view('livewire.bukti-pengeluarans.bukti-pengeluarans-list')
            ->layout('layouts.app');
    }
}