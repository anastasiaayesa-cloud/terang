<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\BuktiPengeluaran;
use App\Models\Perencanaan;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class BuktiPengeluaransList extends Component
{
    public $groupedData = [];

    public $grandTotal = 0;

    public function mount()
    {
        // Load all perencanaans with their bukti
        $perencanaans = Perencanaan::with(['buktiPengeluarans' => function ($q) {
            $q->orderBy('created_at', 'desc');
        }])->get();

        // Group data and calculate totals
        $this->groupedData = $perencanaans->map(function ($perencanaan) {
            return [
                'perencanaan' => $perencanaan,
                'buktiList' => $perencanaan->buktiPengeluarans,
                'totalNominal' => $perencanaan->buktiPengeluarans->sum('nominal'),
                'fileCount' => $perencanaan->buktiPengeluarans->count(),
                'latestUpload' => $perencanaan->buktiPengeluarans->first()?->created_at ?? $perencanaan->created_at,
            ];
        })->sortByDesc('latestUpload')
            ->values();

        // Calculate grand total
        $this->grandTotal = $this->groupedData->sum('totalNominal');
    }

    public function deleteBukti($buktiId)
    {
        $bukti = BuktiPengeluaran::findOrFail($buktiId);

        // Hapus file dari storage
        if (Storage::disk('public')->exists($bukti->file_path)) {
            Storage::disk('public')->delete($bukti->file_path);
        }

        $bukti->delete();

        session()->flash('success', 'Bukti pengeluaran berhasil dihapus.');

        // Refresh data
        $this->mount();
    }

    public function render()
    {
        return view('livewire.bukti-pengeluarans.bukti-pengeluarans-list')
            ->layout('layouts.app');
    }
}
