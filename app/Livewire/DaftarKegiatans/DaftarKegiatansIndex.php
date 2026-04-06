<?php

declare(strict_types=1);

namespace App\Livewire\DaftarKegiatans;

use App\Models\DaftarKegiatan;
use Livewire\Component;
use Livewire\WithPagination;

class DaftarKegiatansIndex extends Component
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

    public function render()
    {
        $daftarKegiatans = DaftarKegiatan::query()
            ->with('sumber')
            ->when($this->search, function ($query) {
                $query->where('nama_kegiatan', 'like', '%'.$this->search.'%');
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('livewire.daftar-kegiatans.daftar-kegiatans-index', compact('daftarKegiatans'))
            ->layout('layouts.app');
    }
}
