<?php

declare(strict_types=1);

namespace App\Livewire\Persuratans;

use App\Models\Kepegawaian;
use App\Models\Persuratan;
use Livewire\Component;
use Livewire\WithFileUploads;

class PersuratansForm extends Component
{
    use WithFileUploads;

    public ?Persuratan $persuratan = null;

    public int $pegawai_id = 0;

    public string $nama_surat = '';

    public $file_pdf = null;

    public $tanggal_upload = null;

    public string $perihal = '';

    public string $jenis_anggaran = 'BPMP';

    protected $rules = [
        'pegawai_id' => 'required|exists:kepegawaians,id',
        'nama_surat' => 'required|string|max:255',
        'file_pdf' => 'nullable|file|mimes:pdf',
        'tanggal_upload' => 'nullable|date',
        'perihal' => 'nullable|string',
        'jenis_anggaran' => 'required|in:BPMP,LUAR BPMP,GABUNGAN',
    ];

    public function mount($persuratan = null): void
    {
        if ($persuratan) {
            $this->persuratan = Persuratan::findOrFail($persuratan);
            $this->pegawai_id = $this->persuratan->pegawai_id;
            $this->nama_surat = $this->persuratan->nama_surat;
            $this->tanggal_upload = $this->persuratan->tanggal_upload ? (string) $this->persuratan->tanggal_upload : null;
            $this->perihal = $this->persuratan->perihal ?? '';
            $this->jenis_anggaran = $this->persuratan->jenis_anggaran ?? 'BPMP';
        }
    }

    public function submit()
    {
        $data = $this->validate();

        if ($this->persuratan) {
            $update = [
                'pegawai_id' => $this->pegawai_id,
                'nama_surat' => $this->nama_surat,
                'tanggal_upload' => $this->tanggal_upload,
                'perihal' => $this->perihal,
                'jenis_anggaran' => $this->jenis_anggaran,
            ];
            if ($this->file_pdf) {
                $path = $this->file_pdf->store('persuratans', 'public');
                $update['file_pdf'] = $path;
            }
            $this->persuratan->update($update);
            session()->flash('success', 'Persuratan berhasil diupdate.');
        } else {
            $path = $this->file_pdf ? $this->file_pdf->store('persuratans', 'public') : null;
            Persuratan::create([
                'pegawai_id' => $this->pegawai_id,
                'nama_surat' => $this->nama_surat,
                'file_pdf' => $path,
                'tanggal_upload' => $this->tanggal_upload ?? now(),
                'perihal' => $this->perihal,
                'jenis_anggaran' => $this->jenis_anggaran,
            ]);
            session()->flash('success', 'Persuratan berhasil disimpan.');
        }

        return redirect()->route('persuratans.index');
    }

    public function render()
    {
        // Pass list of pegawai for dropdown if needed by the view
        return view('livewire.persuratans.persuratans-form', ['pegawais' => Kepegawaian::all()]);
    }
}
