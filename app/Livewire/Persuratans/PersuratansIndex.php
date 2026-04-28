<?php

declare(strict_types=1);

namespace App\Livewire\Persuratans;

use App\Models\Usulan; // Menggunakan Model Usulan sebagai utama
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
        // Mengubah query utama menjadi Usulan::with()
        $usulans = Usulan::with([
                'persuratans', 
                'persuratans.pegawais', 
                'perencanaans', // Memuat relasi perencanaans sesuai catatan
                'usulanPegawais' => function ($query) {
                    $query->where('status', 'approved')->with('kepegawaian');
                }
            ])
            // Filter tetap sama: Hanya usulan yang memiliki pegawai yang sudah disetujui
            ->whereHas('usulanPegawais', function ($query) {
                $query->where('status', 'approved');
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    // Cari di tabel usulan
                    $q->where('nama_kegiatan', 'like', '%' . $this->search . '%')
                    
                    // Cari di tabel perencanaan terkait
                    ->orWhereHas('perencanaans', function ($qq) {
                        $qq->where('kode', 'like', '%' . $this->search . '%')
                           ->orWhere('nama_komponen', 'like', '%' . $this->search . '%');
                    })
                    
                    // Cari berdasarkan data surat (jika sudah ada)
                    ->orWhereHas('persuratans', function ($qq) {
                        $qq->where('nama_surat', 'like', '%' . $this->search . '%')
                           ->orWhere('perihal', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.persuratans.persuratans-index', [
            'usulans' => $usulans, // Tetap menggunakan nama variabel agar tidak merusak view Blade Anda
        ]);
    }

    public function delete(int $id): void
    {
        $persuratan = Persuratan::find($id);

        if ($persuratan) {
            $persuratan->delete();
            session()->flash('success', 'Surat berhasil dihapus.');
        } else {
            session()->flash('error', 'Surat tidak ditemukan.');
        }
    }
}