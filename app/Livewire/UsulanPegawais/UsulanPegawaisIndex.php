<?php

declare(strict_types=1);

namespace App\Livewire\UsulanPegawais;

use App\Models\Usulan;
use App\Models\UsulanPegawai;
// (policy-based authorization removed per request)
use Livewire\Component;
use Livewire\WithPagination;

class UsulanPegawaisIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $filterStatus = '';

    // tidak menyimpan input alasan lagi

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
        $usulanPegawai = UsulanPegawai::findOrFail($id);
        $usulanPegawai->update(['status' => 'approved']);
        session()->flash('success', 'Usulan pegawai berhasil disetujui.');
    }

    public function reject($id)
    {
        $usulanPegawai = UsulanPegawai::findOrFail($id);
        $usulanPegawai->update(['status' => 'rejected']);
        session()->flash('success', 'Usulan pegawai berhasil ditolak.');
    }

    public function delete($usulanPegawaiId)
    {
        $usulanPegawai = UsulanPegawai::findOrFail($usulanPegawaiId);
        $usulanPegawai->delete();
        session()->flash('success', 'Usulan pegawai berhasil dihapus.');
    }

    public function render()
    {
        $usulans = Usulan::query()
            ->with(['usulanPegawais.kepegawaian'])
            ->when($this->search, function ($query) {
                $query->where('nama_kegiatan', 'like', '%'.$this->search.'%');
            })
            ->when($this->filterStatus, function ($query) {
                $query->whereHas('usulanPegawais', function ($q) {
                    $q->where('status', $this->filterStatus);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.usulan-pegawais.usulan-pegawais-index', compact('usulans'));
    }
}
