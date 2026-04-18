<?php

declare(strict_types=1);

namespace App\Livewire\UsulanPegawais;

use App\Models\Usulan;
use App\Models\UsulanPegawai;
use Livewire\Component;
use Livewire\WithPagination;

class UsulanPegawaisIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $filterStatus = '';

    // Properti untuk Modal Penolakan
    public $showModal = false;

    public $selectedId;

    public $rejected_reason; // Input alasan dari modal

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function approve($id)
    {
        $usulanPegawai = UsulanPegawai::findOrFail($id);
        $usulanPegawai->update(['status' => 'approved']);
        session()->flash('success', 'Usulan pegawai berhasil disetujui.');
    }

    /**
     * Membuka modal dan mencatat ID yang akan ditolak
     */
    public function confirmReject($id)
    {
        $this->selectedId = $id;
        $this->rejected_reason = ''; // Reset form alasan
        $this->showModal = true;
    }

    /**
     * Memproses penolakan dengan alasan (Reject_reason)
     */
    public function processReject()
    {
        // Validasi wajib isi alasan
        $this->validate([
            'rejected_reason' => 'required|string|min:5',
        ], [
            'rejected_reason.required' => 'Alasan penolakan wajib diisi!',
            'rejected_reason.min' => 'Alasan minimal 5 karakter.',
        ]);

        $usulanPegawai = UsulanPegawai::findOrFail($this->selectedId);

        $usulanPegawai->update([
            'status' => 'rejected',
            'reject_reason' => $this->rejected_reason, // Sesuai nama kolom database kamu
        ]);

        $this->showModal = false; // Tutup modal
        session()->flash('success', 'Usulan pegawai berhasil ditolak dengan alasan.');
    }

    /**
     * Fungsi reject lama (opsional dihapus jika sudah pakai confirmReject)
     */
    public function reject($id)
    {
        $this->confirmReject($id);
    }

    public function delete($usulanPegawaiId)
    {
        $usulanPegawai = UsulanPegawai::findOrFail($usulanPegawaiId);
        $usulanPegawai->delete();
        session()->flash('success', 'Usulan pegawai berhasil dihapus.');
    }

    public function render()
    {
        $usulans = Usulan::query()
            ->with(['usulanPegawais.kepegawaian'])
            ->when($this->search, function ($query) {
                $query->where('nama_kegiatan', 'like', '%'.$this->search.'%');
            })
            ->when($this->filterStatus, function ($query) {
                $query->whereHas('usulanPegawais', function ($q) {
                    $q->where('status', $this->filterStatus);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.usulan-pegawais.usulan-pegawais-index', compact('usulans'))
            ->layout('layouts.app');
    }
}
