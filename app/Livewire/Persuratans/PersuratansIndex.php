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
        // 1. Perbaikan pada query Riwayat Persuratan (Ganti 'pegawai' jadi 'pegawais')
        $persurats = Persuratan::with([
                'perencanaan',
                'perencanaan.usulan',
                'pegawais', // PERBAIKAN: Menggunakan nama relasi BelongsToMany dari Model
                'kategori'  // Tambahkan eager load kategori jika diperlukan di index
            ])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    // Cari berdasarkan data surat
                    $q->where('nama_surat', 'like', '%'.$this->search.'%')
                      ->orWhere('perihal', 'like', '%'.$this->search.'%')
                      
                    // Cari di tabel perencanaan
                    ->orWhereHas('perencanaan', function ($qq) {
                        $qq->where('kode', 'like', '%'.$this->search.'%')
                            ->orWhere('nama_komponen', 'like', '%'.$this->search.'%')
                            ->orWhereHas('usulan', function ($qqq) {
                                // PERBAIKAN TYPO: nama_kegatan -> nama_kegiatan (sesuaikan database Anda)
                                $qqq->where('nama_kegatan', 'like', '%'.$this->search.'%');
                            });
                    })
                    
                    // PERBAIKAN: Cari di nama pegawai melalui relasi PIVOT (pegawais)
                    ->orWhereHas('pegawais', function ($qq) {
                        $qq->where('nama', 'like', '%'.$this->search.'%');
                    });
                });
            })
            ->orderBy('tanggal_upload', 'desc')
            ->paginate(10);

        // 2. Query Perencanaan yang siap dibuatkan surat (Pegawai status Approved)
        $perencanaansSiapSurat = Perencanaan::whereNotNull('usulan_id')
            ->whereHas('usulan.usulanPegawais', function ($query) {
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
            // Jika menggunakan pivot, data di tabel pivot otomatis terhapus jika di migrasi diset onDelete('cascade')
            // Jika tidak, Anda bisa hapus manual: $persuratan->pegawais()->detach();
            $persuratan->delete();
            session()->flash('success', 'Surat berhasil dihapus.');
        } else {
            session()->flash('error', 'Surat tidak ditemukan.');
        }
    }
}