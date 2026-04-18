<?php

declare(strict_types=1);

namespace App\Livewire\UsulanPegawais;

use App\Models\UsulanPegawai;
use Livewire\Component;

class UsulanPegawaiDetail extends Component
{
    public int $proposalId;

    public UsulanPegawai $proposal;

    public string $reason = '';

    protected $rules = [
        'reason' => 'required|string|max:1000',
    ];

    public function mount(int $id)
    {
        $this->proposal = UsulanPegawai::with(['usulan', 'kepegawaian'])->findOrFail($id);
        $this->proposalId = $id;
    }

    public function submitReject()
    {
        $this->validate();
        // Simpan alasan dan status
        $this->proposal->reject($this->reason);
        session()->flash('success', 'Alasan penolakan tersimpan.');
        // Optional: refresh state
        $this->dispatch('reload');
    }

    public function render()
    {
        return view('livewire.usulan-pegawais.usulan-pegawais-detail');
    }
}
