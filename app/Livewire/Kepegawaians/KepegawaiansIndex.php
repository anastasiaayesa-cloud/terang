<?php

namespace App\Livewire\Kepegawaians;

use App\Models\Kepegawaian;
use Livewire\Component;
use Livewire\WithPagination;

class KepegawaiansIndex extends Component
{
    use WithPagination;

    public $search = '';

    // biar gak error di Tailwind pagination
    protected $paginationTheme = 'tailwind';

    // reset pagination ke halaman 1 tiap kali search berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }

   public function render()
{
    $kepegawaians = Kepegawaian::query()
        // ini relasi
        ->with(['pangkat', 'bank', 'pendidikan']) // penting kalau dipakai di blade (belum tambah instansi)
        ->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('kepegawaians.nama', 'like', '%' . $this->search . '%')
                  ->orWhere('kepegawaians.nip', 'like', '%' . $this->search . '%');
            });
        })
        ->orderBy('id', 'desc')
        ->paginate(5);

    return view('livewire.kepegawaians.kepegawaians-index', compact('kepegawaians'))
        ->layout('layouts.app');
}
}
