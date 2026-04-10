<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\PengajuanPencairan;
use Livewire\Component;

class PengajuanPencairanDetail extends Component
{
    public $pengajuan;

    public $catatanReviewer = '';

    public function mount($id)
    {
        $this->pengajuan = PengajuanPencairan::with(['pegawai', 'perencanaan', 'buktiPengeluarans'])
            ->findOrFail($id);
    }

    public function approve()
    {
        $this->pengajuan->update([
            'status' => 'approved',
            'catatan_reviewer' => $this->catatanReviewer,
        ]);

        session()->flash('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject()
    {
        $this->pengajuan->update([
            'status' => 'rejected',
            'catatan_reviewer' => $this->catatanReviewer,
        ]);

        session()->flash('success', 'Pengajuan berhasil ditolak.');
    }

    public function cairkan()
    {
        $this->pengajuan->update([
            'status' => 'dicairkan',
            'tanggal_cair' => now(),
        ]);

        session()->flash('success', 'Pengajuan berhasil dicairkan.');
    }

    public function render()
    {
        return view('livewire.pengajuan-pencairan-detail')
            ->layout('layouts.app');
    }
}
