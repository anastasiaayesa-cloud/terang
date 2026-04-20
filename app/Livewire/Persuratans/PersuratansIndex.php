<?php

declare(strict_types=1);

namespace App\Livewire\Persuratans;

use App\Models\Perencanaan;
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
        // Perbaikan pada query Riwayat Persuratan
        $persurats = Persuratan::with([
                'perencanaan',
                'perencanaan.usulan',
                'pegawai',
            ])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    // Cari di tabel perencanaan
                    $q->whereHas('perencanaan', function ($qq) {
                        $qq->where('kode', 'like', '%'.$this->search.'%')
                            ->orWhere('nama_komponen', 'like', '%'.$this->search.'%')
                            ->orWhereHas('usulan', function ($qqq) {
                                $qqq->where('nama_kegatan', 'like', '%'.$this->search.'%');
                            });
                    })
                    // Atau cari di nama pegawai
                    ->orWhereHas('pegawai', function ($qq) {
                        $qq->where('nama', 'like', '%'.$this->search.'%');
                    });
                });
            })
            ->orderBy('tanggal_upload', 'desc')
            ->paginate(10);

        // Query Perencanaan yang siap dibuatkan surat (Pegawai status Approved)
        $perencanaansSiapSurat = Perencanaan::whereNotNull('usulan_id')
            ->whereHas('usulan.usulanPegawais', function ($query) {
                // Sesuai input Anda: status ada di tabel usulan_pegawai
                $query->where('status', 'approved');
            })
            ->with(['usulan', 'usulan.usulanPegawais' => function ($query) {
                $query->where('status', 'approved')->with('kepegawaian');
            }])
            ->get();

        return view('livewire.persuratans.persuratans-index', [
            'persurats' => $persurats,
            'perencanaansSiapSurat' => $perencanaansSiapSurat,
        ]);
    }

    public function delete(int $id): void
    {
        $persuratan = Persuratan::find($id);

        if ($persuratan) {
            // Jika ingin menghapus satu baris saja:
            $persuratan->delete();
            
            // ATAU Jika ingin menghapus semua surat dalam perencanaan yang sama:
            // Persuratan::where('perencanaan_id', $persuratan->perencanaan_id)->delete();

            session()->flash('success', 'Surat berhasil dihapus.');
        } else {
            session()->flash('error', 'Surat tidak ditemukan.');
        }
    }
}