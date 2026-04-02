<?php

declare(strict_types=1);

namespace App\Livewire\Perencanaans;

use App\Models\Perencanaan;
use Livewire\Component;
use Livewire\WithPagination;

class PerencanaansIndex extends Component
{
    use WithPagination;

    public $search = '';

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $perencanaans = Perencanaan::query()
            ->with(['dokumenPerencanaan', 'buktiPengeluarans', 'usulanPembayarans'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('perencanaans.kode', 'like', '%'.$this->search.'%')
                        ->orWhere('perencanaans.nama_komponen', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(5);

        return view('livewire.perencanaans.perencanaans-index', compact('perencanaans'))
            ->layout('layouts.app');
    }
}
