<?php

namespace App\Livewire\Usulans;

use App\Models\Usulan;
use Livewire\Component;
use Livewire\WithPagination;

class UsulansIndex extends Component
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
        $usulan = Usulan::findOrFail($id);
        $usulan->update(['status' => 'approved']);
        session()->flash('success', 'Usulan berhasil disetujui.');
    }

    public function reject($id)
    {
        $usulan = Usulan::findOrFail($id);
        $usulan->update(['status' => 'rejected']);
        session()->flash('success', 'Usulan berhasil ditolak.');
    }

    public function delete($id)
    {
        $usulan = Usulan::findOrFail($id);
        $usulan->delete();
        session()->flash('success', 'Usulan berhasil dihapus.');
    }

    public function render()
    {
        $usulans = Usulan::query()
            ->with('kepegawaian')
            ->when($this->search, function ($query) {
                $query->where('nama_kegiatan', 'like', '%'.$this->search.'%');
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('livewire.usulans.usulans-index', compact('usulans'))
            ->layout('layouts.app');
    }
}
