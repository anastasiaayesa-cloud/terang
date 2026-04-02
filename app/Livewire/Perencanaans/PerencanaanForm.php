<?php

declare(strict_types=1);

namespace App\Livewire\Perencanaans;

use App\Models\DokumenPerencanaan;
use App\Models\Perencanaan;
use App\Models\Usulan;
use Livewire\Component;

class PerencanaanForm extends Component
{
    public $perencanaan_id;

    public $kode;

    public $nama_komponen;

    public $dokumen_perencanaan_id;

    public $usulan_id;

    public function mount($perencanaan_id = null)
    {
        if ($perencanaan_id) {
            $this->perencanaan_id = $perencanaan_id;
            $perencanaan = Perencanaan::findOrFail($perencanaan_id);

            $this->kode = $perencanaan->kode;
            $this->nama_komponen = $perencanaan->nama_komponen;
            $this->dokumen_perencanaan_id = $perencanaan->dokumen_perencanaan_id;
            $this->usulan_id = $perencanaan->usulan_id;
        }
    }

    public function rules()
    {
        return [
            'kode' => 'nullable|string|max:255',
            'nama_komponen' => 'required|string|max:255',
            'dokumen_perencanaan_id' => 'required|exists:dokumen_perencanaans,id',
            'usulan_id' => 'nullable|exists:usulans,id',
        ];
    }

    public function submit()
    {
        $this->validate();

        if ($this->perencanaan_id) {
            $perencanaan = Perencanaan::findOrFail($this->perencanaan_id);

            $perencanaan->update([
                'kode' => $this->kode,
                'nama_komponen' => $this->nama_komponen,
                'dokumen_perencanaan_id' => $this->dokumen_perencanaan_id,
                'usulan_id' => $this->usulan_id,
            ]);

            session()->flash('success', 'Perencanaan berhasil diperbarui.');

            return redirect()->route('perencanaans.index');
        }

        Perencanaan::create([
            'kode' => $this->kode,
            'nama_komponen' => $this->nama_komponen,
            'dokumen_perencanaan_id' => $this->dokumen_perencanaan_id,
            'usulan_id' => $this->usulan_id,
        ]);

        session()->flash('success', 'Perencanaan baru berhasil ditambahkan.');

        return redirect()->route('perencanaans.index');
    }

    public function render()
    {
        $dokumenPerencanaans = DokumenPerencanaan::orderBy('nama', 'asc')->get();
        $usulans = Usulan::orderBy('nama_kegiatan', 'asc')->get();

        return view('livewire.perencanaans.perencanaan-form', compact('dokumenPerencanaans', 'usulans'))
            ->layout('layouts.app');
    }
}
