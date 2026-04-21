<?php

namespace App\Livewire\LaporanKegiatans;

use App\Models\LaporanKegiatan;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UsulanPegawai;

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
        // Cari berdasarkan ID di tabel laporan_kegiatans
        $laporan = LaporanKegiatan::find($id);
    
        if ($laporan) {
            $laporan->update(['status' => 'approved']);
            session()->flash('success', 'Laporan berhasil disetujui.');
        } else {
            session()->flash('error', 'Data laporan tidak ditemukan.');
        }
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
        $approvedUsulanPegawai = UsulanPegawai::query()
            ->where('status', 'approved')
            ->with(['kepegawaian', 'usulan'])
            ->paginate(10);

        // Pastikan file_laporan dipetakan di sini
        $laporanMap = LaporanKegiatan::all()->mapWithKeys(function ($item) {
            return [$item->usulan_id . '-' . $item->pegawai_id => [
                'id' => $item->id,
                'status' => $item->status,
                'file_laporan' => $item->file_laporan, 
            ]];
        })->toArray();

        return view('livewire.laporan-kegiatans.laporan-kegiatans-index', compact('approvedUsulanPegawai', 'laporanMap'));
    }
}
