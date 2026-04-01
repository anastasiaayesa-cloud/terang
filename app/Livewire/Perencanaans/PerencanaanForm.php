<?php

declare(strict_types=1);

namespace App\Livewire\Perencanaans;

use App\Models\DokumenPerencanaan;
use App\Models\Perencanaan;
use Livewire\Component;

class PerencanaanForm extends Component
{
    public $perencanaan_id;

    public $kode;

    public $nama_komponen;

    public $file_pdf;

    public function mount($perencanaan_id = null)
    {
        if ($perencanaan_id) {
            $this->perencanaan_id = $perencanaan_id;
            $perencanaan = Perencanaan::findOrFail($perencanaan_id);

            $this->kode = $perencanaan->kode;
            $this->nama_komponen = $perencanaan->nama_komponen;
            $this->file_pdf = $perencanaan->file_pdf;
        }
    }

    public function rules()
    {
        return [
            'kode' => 'nullable|string|max:255',
            'nama_komponen' => 'required|string|max:255',
            'file_pdf' => 'required|exists:dokumen_perencanaans,id',
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
                'file_pdf' => $this->file_pdf,
            ]);

            session()->flash('success', 'Perencanaan berhasil diperbarui.');

            return redirect()->route('perencanaans.index');
        }

        Perencanaan::create([
            'kode' => $this->kode,
            'nama_komponen' => $this->nama_komponen,
            'file_pdf' => $this->file_pdf,
        ]);

        session()->flash('success', 'Perencanaan baru berhasil ditambahkan.');

        return redirect()->route('perencanaans.index');
    }

    public function render()
    {
        $dokumenPerencanaans = DokumenPerencanaan::orderBy('nama', 'asc')->get();

        return view('livewire.perencanaans.perencanaan-form', compact('dokumenPerencanaans'))
            ->layout('layouts.app');
    }
}
