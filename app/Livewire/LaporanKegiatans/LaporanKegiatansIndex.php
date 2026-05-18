<?php

namespace App\Livewire\LaporanKegiatans;

use App\Models\LaporanKEGIATAN;
use App\Models\Usulan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class LaporanKegiatansIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $filterStatus = '';

    public $hasKepegawaian = false;

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
        $laporan = LaporanKEGIATAN::find($id);

        if ($laporan) {
            $laporan->update(['status' => 'approved']);
            session()->flash('success', 'Laporan berhasil disetujui.');
        } else {
            session()->flash('error', 'Data laporan tidak ditemukan.');
        }
    }

    public function reject($id)
    {
        $laporan = LaporanKEGIATAN::findOrFail($id);
        $laporan->update(['status' => 'rejected']);
        session()->flash('success', 'Laporan berhasil ditolak.');
    }

    public function delete($id)
    {
        $laporan = LaporanKEGIATAN::findOrFail($id);
        if ($laporan->file_laporan && \Storage::disk('public')->exists($laporan->file_laporan)) {
            \Storage::disk('public')->delete($laporan->file_laporan);
        }
        $laporan->delete();
        session()->flash('success', 'Laporan berhasil dihapus.');
    }

    public function isSuperAdmin(): bool
    {
        return Auth::user()?->hasRole('Super Admin');
    }

    public function getCurrentKepegawaianId(): ?int
    {
        $kepegawaianId = Auth::user()?->kepegawaian?->id;
        $this->hasKepegawaian = $kepegawaianId !== null;

        return $kepegawaianId;
    }

    public function render()
    {
        $query = Usulan::query()
            ->whereHas('usulanPegawais', function ($q) {
                $q->where('status', 'approved');
            })
            ->with(['usulanPegawais.kepegawaian']);

        if (! $this->isSuperAdmin()) {
            $kepegawaianId = $this->getCurrentKepegawaianId();
            if (! $this->hasKepegawaian) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereHas('usulanPegawais', function ($q) use ($kepegawaianId) {
                    $q->where('pegawai_id', $kepegawaianId);
                });
            }
        }

        if ($this->search) {
            $query->where('nama_kegiatan', 'like', '%'.$this->search.'%');
        }

        $activities = $query->paginate(10);

        $laporanMap = LaporanKEGIATAN::all()->mapWithKeys(function ($item) {
            return [$item->usulan_id.'-'.$item->pegawai_id => [
                'id' => $item->id,
                'status' => $item->status,
                'file_laporan' => $item->file_laporan,
            ]];
        })->toArray();

        $isSuperAdmin = $this->isSuperAdmin();

        return view('livewire.laporan-kegiatans.laporan-kegiatans-index', compact('activities', 'laporanMap', 'isSuperAdmin'));
    }
}
