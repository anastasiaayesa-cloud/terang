<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\UsulanPembayaran;
use Livewire\Component;

class UsulanPembayaransIndex extends Component
{
    public $usulans = [];

    public $grandTotal = 0;

    public function mount()
    {
        $this->usulans = UsulanPembayaran::with(['perencanaan', 'pegawai.pangkat'])
            ->orderBy('created_at', 'desc')
            ->get();

        $this->grandTotal = $this->usulans->sum('total_nominal');
    }

    public function delete($usulanId)
    {
        $usulan = UsulanPembayaran::findOrFail($usulanId);
        $usulan->delete();

        session()->flash('success', 'Usulan pembayaran berhasil dihapus.');

        $this->usulans = UsulanPembayaran::with(['perencanaan', 'pegawai.pangkat'])
            ->orderBy('created_at', 'desc')
            ->get();

        $this->grandTotal = $this->usulans->sum('total_nominal');
    }

    public function render()
    {
        return view('livewire.usulan-pembayarans.usulan-pembayarans-index')
            ->layout('layouts.app');
    }
}
