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
        // Mengambil data Perencanaan sebagai data utama (Induk)
        // Kita memuat relasi 'persuratans' untuk mengecek apakah sudah ada surat atau belum
        $perencanaans = Perencanaan::with([
                'persuratans', 
                'persuratans.pegawais', // Untuk daftar pegawai di riwayat
                'usulan', 
                'usulan.usulanPegawais' => function ($query) {
                    $query->where('status', 'approved')->with('kepegawaian');
                }
            ])
            ->whereNotNull('usulan_id') // Pastikan hanya perencanaan yang punya usulan
            ->whereHas('usulan.usulanPegawais', function ($query) {
                $query->where('status', 'approved'); // Hanya yang pegawainya sudah disetujui
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    // Cari di tabel perencanaan
                    $q->where('kode', 'like', '%' . $this->search . '%')
                    ->orWhere('nama_komponen', 'like', '%' . $this->search . '%')
                    
                    // Cari di tabel usulan
                    ->orWhereHas('usulan', function ($qq) {
                        $qq->where('nama_kegiatan', 'like', '%' . $this->search . '%');
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
            'perencanaans' => $perencanaans, // Ini variabel yang dipanggil di Blade @forelse
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