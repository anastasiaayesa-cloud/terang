<?php

declare(strict_types=1);

namespace App\Livewire\UsulanPegawais;

use App\Models\Kepegawaian;
use App\Models\Usulan;
use App\Models\UsulanPegawai;
use Livewire\Component;

class UsulanPegawaisForm extends Component
{
    public $usulanPegawai;

    public $usulan_id;

    public $pegawai_ids = [];

    // public $catatan;

    public $rejected_reason;

    public $alasan;

    public $pegawais;

    public function mount($usulanPegawai = null, $usulan_id = null)
    {
        $this->pegawais = Kepegawaian::all();

        if ($usulan_id) {
            $this->usulan_id = $usulan_id;
            $this->usulan = Usulan::findOrFail($usulan_id);
        }

        if ($usulanPegawai) {
            $this->usulanPegawai = UsulanPegawai::findOrFail($usulanPegawai);
            $this->usulan = $this->usulanPegawai->usulan;
            $this->usulan_id = $this->usulanPegawai->usulan_id;
            $this->pegawai_ids = [$this->usulanPegawai->pegawai_id];
            $this->catatan = $this->usulanPegawai->catatan;
            $this->rejected_reason = $this->usulanPegawai->rejected_reason;
        }
    }

    public function rules()
    {
        return [
            'usulan_id' => 'required|exists:usulans,id',
            'pegawai_ids' => 'required|array|min:1',
            'pegawai_ids.*' => 'exists:kepegawaians,id',
            'catatan' => 'nullable|string',
            'rejected_reason' => 'required|string',
        ];
    }

    public function submit()
    {
        $data = $this->validate();

        if ($this->usulanPegawai) {
            $this->usulanPegawai->update([
                'pegawai_id' => $data['pegawai_ids'][0],
                'rejected_reason' => $data['catatan'],
            ]);
            session()->flash('success', 'Usulan pegawai berhasil diupdate.');
        } else {
            foreach ($data['pegawai_ids'] as $pegawaiId) {
                UsulanPegawai::create([
                    'usulan_id' => $data['usulan_id'],
                    'pegawai_id' => $pegawaiId,
                    'status' => 'pending',
                    'rejected_reason' => $data['rejected_reason'],
                ]);
            }
            session()->flash('success', count($data['pegawai_ids']).' pegawai berhasil ditambahkan.');
        }

        return redirect()->route('usulan-pegawais.index');
    }

    public function delete()
    {
        if ($this->usulanPegawai) {
            $this->usulanPegawai->delete();
            session()->flash('success', 'Usulan pegawai berhasil dihapus.');
        }

        return redirect()->route('usulan-pegawais.index');
    }

    public function render()
    {
        return view('livewire.usulan-pegawais.usulan-pegawais-form')
            ->layout('layouts.app');
    }
}
