<?php

namespace App\Livewire\DaftarKegiatans;

use App\Models\DaftarActivities;
use Livewire\Component;

class DaftarKegiatansForm extends Component
{
    public $activityId;

    public $tujuan_kegiatan;

    public $waktu_kegiatan;

    public $keterangan;

    public $showModal = false;

    // Listener agar bisa dipanggil dari komponen Index
    protected $listeners = ['openEditModal' => 'loadActivity'];

    public function loadActivity($id)
    {
        $activity = DaftarActivities::findOrFail($id);
        $this->activityId = $id;
        $this->tujuan_kegiatan = $activity->tujuan_kegiatan;
        $this->waktu_kegiatan = $activity->waktu_kegiatan ? $activity->waktu_kegiatan->format('Y-m-d') : '';
        $this->keterangan = $activity->keterangan;

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'tujuan_kegiatan' => 'required|string|max:255',
            'waktu_kegiatan' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $activity = DaftarActivities::find($this->activityId);
        $activity->update([
            'tujuan_kegiatan' => $this->tujuan_kegiatan,
            'waktu_kegiatan' => $this->waktu_kegiatan,
            'keterangan' => $this->keterangan,
        ]);

        $this->showModal = false;
        $this->dispatch('refreshIndex'); // Beritahu Index untuk refresh data
        session()->flash('success', 'Detail kegiatan berhasil diperbarui!');
    }

    public function render()
    {
        // Pastikan folder 'daftar-kegiatans' ada di resources/views/livewire/
        return view('livewire.daftar-kegiatans.daftar-kegiatans-form');
    }
}
