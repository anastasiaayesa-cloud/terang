<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\PengajuanPencairan;
use Livewire\Component;
use Livewire\WithPagination;

class PengajuanPencairansIndex extends Component
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
        $pengajuan = PengajuanPencairan::findOrFail($id);
        $pengajuan->update([
            'status' => 'approved',
            'catatan_reviewer' => $pengajuan->catatan_reviewer,
        ]);
        session()->flash('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject($id)
    {
        $pengajuan = PengajuanPencairan::findOrFail($id);
        $pengajuan->update(['status' => 'rejected']);
        session()->flash('success', 'Pengajuan berhasil ditolak.');
    }

    public function cairkan($id)
    {
        $pengajuan = PengajuanPencairan::findOrFail($id);
        $pengajuan->update([
            'status' => 'dicairkan',
            'tanggal_cair' => now(),
        ]);
        session()->flash('success', 'Pengajuan berhasil dicairkan.');
    }

    public function delete($id)
    {
        $pengajuan = PengajuanPencairan::findOrFail($id);

        if ($pengajuan->status !== 'pending') {
            session()->flash('error', 'Hanya pengajuan dengan status pending yang bisa dihapus.');

            return;
        }

        $pengajuan->delete();
        session()->flash('success', 'Pengajuan berhasil dihapus.');
    }

    public function render()
    {
        $pengajuans = PengajuanPencairan::query()
            ->with(['pegawai', 'perencanaan', 'buktiPengeluarans'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nomor_surat', 'like', '%'.$this->search.'%')
                        ->orWhereHas('pegawai', function ($q) {
                            $q->where('nama', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('perencanaan', function ($q) {
                            $q->where('nama_komponen', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.pengajuan-pencairans-index', compact('pengajuans'))
            ->layout('layouts.app');
    }
}
