<?php

namespace App\Livewire\DokumenPerencanaans;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DokumenPerencanaan;

class DokumenPerencanaanIndex extends Component
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
        $dokumenperencanaans = DokumenPerencanaan::query()
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'asc')
            ->paginate(5);

        return view('livewire.dokumen-perencanaans.dokumen-perencanaan-index', [
            'dokumenperencanaans' => $dokumenperencanaans
        ])->layout('layouts.app');
    }
}