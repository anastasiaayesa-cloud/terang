<?php

namespace App\Livewire\Usulans;

use App\Models\Kepegawaian;
use App\Models\Usulan;
use Livewire\Component;

class UsulansForm extends Component
{
    public $usulan;

    public $pegawaiList = [];

    public $pegawai_id;

    public $nama_kegiatan;

    public $tanggal_kegiatan;

    public $lokasi_kegiatan;

    public $deskripsi;

    public function mount($usulan = null)
    {
        $this->pegawaiList = Kepegawaian::orderBy('nama')->get();

        if ($usulan) {
            $this->usulan = Usulan::findOrFail($usulan);
            $this->fill($this->usulan->only([
                'pegawai_id',
                'nama_kegiatan',
                'tanggal_kegiatan',
                'lokasi_kegiatan',
                'deskripsi',
            ]));
        }
    }

    public function rules()
    {
        return [
            'pegawai_id' => 'required|exists:kepegawaians,id',
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal_kegiatan' => 'required|date',
            'lokasi_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ];
    }

    public function submit()
    {
        $data = $this->validate();

        if ($this->usulan) {
            $this->usulan->update($data);
            session()->flash('success', 'Usulan berhasil diupdate.');
        } else {
            $data['status'] = 'pending';
            Usulan::create($data);
            session()->flash('success', 'Usulan berhasil ditambahkan.');
        }

        return redirect()->route('usulans.index');
    }

    public function delete()
    {
        if ($this->usulan) {
            $this->usulan->delete();
            session()->flash('success', 'Usulan berhasil dihapus.');
        }

        return redirect()->route('usulans.index');
    }

    public function render()
    {
        return view('livewire.usulans.usulans-form')
            ->layout('layouts.app');
    }
}
