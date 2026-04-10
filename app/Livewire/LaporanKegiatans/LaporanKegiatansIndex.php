<?php

namespace App\Livewire\LaporanKegiatans;

use App\Models\LaporanKegiatan;
use Livewire\Component;
use Livewire\WithPagination;

class LaporanKegiatansIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $filterStatus = '';

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function approve($id)
    {
        $laporan = LaporanKegiatan::findOrFail($id);
        $laporan->update(['status' => 'approved']);
        session()->flash('success', 'Laporan berhasil disetujui.');
    }

    public function reject($id)
    {
        $laporan = LaporanKegiatan::findOrFail($id);
        $laporan->update(['status' => 'rejected']);
        session()->flash('success', 'Laporan berhasil ditolak.');
    }

    public function delete($id)
    {
        $laporan = LaporanKegiatan::findOrFail($id);
        if ($laporan->file_laporan && \Storage::disk('public')->exists($laporan->file_laporan)) {
            \Storage::disk('public')->delete($laporan->file_laporan);
        }
        $laporan->delete();
        session()->flash('success', 'Laporan berhasil dihapus.');
    }

    public function render()
    {
        $laporanKegiatans = LaporanKegiatan::query()
            ->with(['kepegawaian', 'perencanaan'])
            ->when($this->search, function ($query) {
                $query->where('judul_laporan', 'like', '%'.$this->search.'%');
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('livewire.laporan-kegiatans.laporan-kegiatans-index', compact('laporanKegiatans'))
            ->layout('layouts.app');
    }
}
