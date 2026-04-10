<?php

declare(strict_types=1);

namespace App\Livewire\Persuratans;

use App\Models\Persuratan;
use Livewire\Component;
use Livewire\WithPagination;

class PersuratansIndex extends Component
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
        $persurats = Persuratan::with('pegawai')
            ->orderBy('tanggal_upload', 'desc')
            ->get(['id', 'pegawai_id', 'nama_surat', 'tanggal_upload', 'perihal', 'jenis_anggaran']);

        return view('livewire.persuratans.persuratans-index', ['persurats' => $persurats]);
    }
}
