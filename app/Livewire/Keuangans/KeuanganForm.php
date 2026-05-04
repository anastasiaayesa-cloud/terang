<?php

namespace App\Livewire;

namespace App\Livewire\Keuangans;

use App\Models\Keuangan;
use App\Models\Pegawai;
use App\Models\Usulan;
use Livewire\Component;

class KeuanganForm extends Component
{
    // Form Fields
    public $usulan_id;

    public $pegawai_id;

    public $perincian_bayar;

    public $nominal = 0;

    public $jumlah = 1;

    public $jenis = 'BIAYA PERJALANAN DINAS';

    // Helpers
    public $pegawaiTersedia = [];

    public function updatedUsulanId($value)
    {
        // Ambil pegawai yang sudah APPROVED pada usulan yang dipilih
        // Melalui tabel pivot usulan_pegawais
        $this->pegawaiTersedia = Pegawai::whereHas('usulanPegawais', function ($q) use ($value) {
            $q->where('usulan_id', $value)->where('status', 'approved');
        })->get();

        $this->pegawai_id = null; // Reset pilihan pegawai
    }

    public function save()
    {
        $this->validate([
            'usulan_id' => 'required',
            'pegawai_id' => 'required',
            'perincian_bayar' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'jumlah' => 'required|integer|min:1',
            'jenis' => 'required|in:BIAYA PERJALANAN DINAS,PENGELUARAN RIIL,KEDUANYA',
        ]);

        Keuangan::create([
            'usulan_id' => $this->usulan_id,
            'pegawai_id' => $this->pegawai_id,
            'perincian_bayar' => $this->perincian_bayar,
            'nominal' => $this->nominal,
            'jumlah' => $this->jumlah,
            'jenis' => $this->jenis,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Data Keuangan berhasil disimpan.');

        return redirect()->route('keuangan.index'); // Sesuaikan route Anda
    }

    public function render()
    {
        return view('livewire.keuangan-form', [
            'usulans' => Usulan::all(),
            'total_preview' => $this->nominal * $this->jumlah,
        ]);
    }
}
