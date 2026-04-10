<?php

namespace App\Livewire\Usulans;

use App\Models\Usulan;
use Livewire\Component;
use Livewire\WithPagination;

class UsulansIndex extends Component
{
    use WithPagination;

    public $search = '';

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Status-based actions removed; UI no longer manages status

    public function delete($id)
    {
        $usulan = Usulan::findOrFail($id);
        $usulan->delete();
        session()->flash('success', 'Usulan berhasil dihapus.');
    }

    public function render()
    {
        $usulans = Usulan::query()
            ->when($this->search, function ($query) {
                $query->where('nama_kegiatan', 'like', '%'.$this->search.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('livewire.usulans.usulans-index', compact('usulans'));
    }
}
