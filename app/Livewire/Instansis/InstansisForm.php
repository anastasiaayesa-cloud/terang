<?php

declare(strict_types=1);

namespace App\Livewire\Instansis;

use App\Models\Instansi;
use App\Models\Kabupaten;
use Livewire\Component;

class InstansisForm extends Component
{
    public $instansi_id;

    public $nama;

    public $alamat;

    public $telp;

    public $kabupaten_id;

    public $kabupatenList = [];

    public function mount($instansi_id = null)
    {
        $this->kabupatenList = Kabupaten::orderBy('nama')->get();

        if ($instansi_id) {
            $this->instansi_id = $instansi_id;

            $instansi = Instansi::findOrFail($instansi_id);

            $this->nama = $instansi->nama;
            $this->alamat = $instansi->alamat;
            $this->telp = $instansi->telp;
            $this->kabupaten_id = $instansi->kabupaten_id;
        }
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'telp' => 'required|string|max:255',
            'kabupaten_id' => 'required|exists:kabupatens,id',
        ];
    }

    public function submit()
    {
        $this->validate();

        $data = [
            'nama' => $this->nama,
            'alamat' => $this->alamat,
            'telp' => $this->telp,
            'kabupaten_id' => $this->kabupaten_id,
        ];

        if ($this->instansi_id) {
            $instansi = Instansi::findOrFail($this->instansi_id);
            $instansi->update($data);

            session()->flash('success', 'Instansi berhasil diperbarui.');
        } else {
            Instansi::create($data);

            session()->flash('success', 'Instansi baru berhasil ditambahkan.');
        }

        return redirect()->route('instansis.index');
    }

    public function render()
    {
        return view('livewire.instansis.instansis-form')
            ->layout('layouts.app');
    }
}
