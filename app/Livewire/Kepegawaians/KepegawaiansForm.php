<?php

namespace App\Livewire\Kepegawaians;

use App\Models\Instansi;
use App\Models\Kepegawaian;
use App\Models\Pangkat;
use App\Models\Bank;
use App\Models\Pendidikan;
use Livewire\Component;

class KepegawaiansForm extends Component
{
    public $kepegawaian;

    public $nama, $nip, $jabatan, $pangkat_id,
        $tempat_lahir, $tgl_lahir, $jenis_kelamin, $agama,
        $instansi_id, $pendidikan_id,
        $hp, $email, $npwp, $bank_id, $no_rek,
        $is_bpmp, $user_id;

    public $pangkatList = [], $instansiList = [], $bankList = [], $pendidikanList = [];

    public function mount($kepegawaian = null)
    {
        // dropdown
        $this->pangkatList = Pangkat::orderBy('nama')->get();
        // $this->instansiList = Instansi::orderBy('nama_instansi')->get();
        $this->bankList = Bank::orderBy('nama')->get();
        $this->pendidikanList = Pendidikan::orderBy('nama')->get();

        // EDIT MODE
        if ($kepegawaian) {
            $this->kepegawaian = Kepegawaian::findOrFail($kepegawaian);

            $this->fill($this->kepegawaian->only([
                'nama',
                'nip',
                'jabatan',
                'pangkat_id',
                'tempat_lahir',
                'tgl_lahir',
                'jenis_kelamin',
                'agama',
                'instansi_id',
                'hp',
                'email',
                'npwp',
                'bank_id',
                'no_rek',
                'pendidikan_id',
                'is_bpmp',
                'user_id',
            ]));
        }
    }

    public function rules()
    {
        return [
            'nama' => 'nullable|string|max:255',
            'nip' => 'nullable|string|max:255', // FIX (bukan int)
            'jabatan' => 'nullable|string|max:255',

            'pangkat_id' => 'nullable|integer',
            'tempat_lahir' => 'nullable|string|max:255',
            'tgl_lahir' => 'nullable|string', // sesuai migration (varchar)

            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'agama' => 'nullable|in:Islam,Kristen Katolik,Kristen Protestan,Hindu,Buddha,Konghucu',

            'instansi_id' => 'nullable|string',
            'hp' => 'nullable|string|max:255',

            'email' => 'required|email|max:255|unique:kepegawaians,email,' . ($this->kepegawaian->id ?? 'NULL') . ',id',

            'npwp' => 'nullable|string|max:255',

            'bank_id' => 'nullable|string',
            'no_rek' => 'nullable|string|max:255',

            'pendidikan_id' => 'nullable|integer',

            'is_bpmp' => 'nullable|string',
            'user_id' => 'nullable|integer',
        ];
    }

    public function submit()
    {
        $data = $this->validate();

        if ($this->kepegawaian) {
            // UPDATE
            $this->kepegawaian->update($data);

            session()->flash('success', 'Kepegawaian berhasil diupdate.');
        } else {
            // CREATE
            Kepegawaian::create($data);

            session()->flash('success', 'Kepegawaian berhasil ditambahkan.');
        }

        return redirect()->route('kepegawaians.index');
    }

    public function delete()
    {
        if ($this->kepegawaian) {
            $this->kepegawaian->delete();

            session()->flash('success', 'Kepegawaian berhasil dihapus.');
        }

        return redirect()->route('kepegawaians.index');
    }

    public function render()
    {
        return view('livewire.kepegawaians.kepegawaians-form');
    }
}