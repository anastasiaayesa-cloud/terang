<?php

namespace App\Livewire\Instansis;

use Livewire\Component;
use App\Models\Kabupaten;
use App\Models\Instansi;

class InstansisForm extends Component
{
public $instansi_id, $nama_instansi, $alamat_instansi, $telp_instansi, $kabupaten_id, $kabupatenList = [];

 public function mount($instansi_id = null)
    {
        $this->kabupatenList = Kabupaten::orderBy('nama')->get();

        // kalau parameter ada (edit mode)
        if ($instansi_id) {
            // $this->authorize('instansi-edit');
            $this->id = $instansi_id;

            $instansi = Instansi::findOrFail($instansi_id);
            $this->nama = $instansi->nama_instansi;
            $this->alamat = $instansi->alamat_instansi;
            $this->telp = $instansi->telp_instansi;
            $this->kabupaten_id = $instansi->kabupaten_id;
        }else{
            // $this->authorize('instansi-create');
        }
    }

     public function rules()
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'telp' => 'required|string|max:255',
            'kabupaten_id' => 'required|exists:kabupatens,id',

        ];

        return $rules;
    }
    
        public function submit()
        {
            if ($this->instansi_id) {
                $this->authorize('instansi-edit');
            } else {
                $this->authorize('instansi-create');
            }

            $this->validate();

            // setelah lulus validasi, lakukan sintaks dibawah
            if ($this->instansi_id) {
                $instansi = Instansi::findOrFail($this->id);
                $instansi->update([
                    'nama' => $this->nama,
                    'alamat' => $this->alamat,
                    'telp' => $this->telp,
                    'kabupaten_id' => $this->kabupaten_id,
                
                ]);

                session()->flash('success', 'Instansi berhasil diedit.');
                return redirect()->route('instansis.index');
            } else {
                $instansi = Instansi::create([
                    'nama' => $this->nama,
                    'alamat' => $this->alamat,
                    'telp' => $this->telp,
                    'kabupaten_id' => $this->kabupaten_id,
                    
                ]);

                session()->flash('success', 'instansi baru berhasil ditambahkan.');
                return redirect()->route('instansis.create');
            }
        }

    public function render()
    {
        return view('livewire.instansis.instansis-form')->layout('layouts.app');
    }
}
