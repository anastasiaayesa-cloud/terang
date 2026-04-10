<?php

declare(strict_types=1);

namespace App\Livewire\Usulans;

use App\Models\Usulan;
use Livewire\Component;

class UsulansForm extends Component
{
    public $usulan;

    public $nama_kegiatan;

    public $tanggal_kegiatan;

    public $lokasi_kegiatan;

    public $deskripsi;

    public function mount($usulan = null)
    {
        if ($usulan) {
            $this->usulan = Usulan::findOrFail($usulan);
            $this->fill($this->usulan->only([
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
            // Status tidak lagi digunakan pada kolom usulan, hilangkan penetapan default
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
        // Render without forcing a specific Livewire layout to avoid environment-specific issues
        return view('livewire.usulans.usulans-form');
    }
}
