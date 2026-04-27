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
        $this->loadData();
    }

    public function loadData()
    {
        $perencanaans = Perencanaan::with([
            // Filter usulanPegawais agar hanya mengambil yang statusnya 'approve'
            'usulan.usulanPegawais' => function ($query) {
                $query->where('status', 'approved')->with('kepegawaian');
            },
            'buktiPengeluarans.kepegawaian'
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

        // Refresh data menggunakan fungsi loadData agar lebih bersih
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.bukti-pengeluarans.bukti-pengeluarans-list')
            ->layout('layouts.app');
    }
}