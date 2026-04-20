<?php

declare(strict_types=1);

namespace App\Livewire\Persuratans;

use App\Models\Perencanaan;
use App\Models\Persuratan;
use App\Models\PersuratanKategori;
use App\Models\UsulanPegawai;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class PersuratansForm extends Component
{
    use WithFileUploads;

    public ?Perencanaan $perencanaan = null;
    public $usulan_pegawais_approved = [];
    public $inputs = [];
    public $i = 0;

    public function mount(int $perencanaan_id): void
    {
        $this->perencanaan = Perencanaan::with(['usulan'])
            ->find($perencanaan_id);

        if (!$this->perencanaan) {
            session()->flash('error', 'Perencanaan tidak ditemukan.');
            $this->redirectRoute('persuratans.index');
            return;
        }

        $this->loadApprovedPegawais();
        $this->addInput(0);
    }

    protected function loadApprovedPegawais(): void
    {
        if (!$this->perencanaan->usulan_id) {
            $this->usulan_pegawais_approved = [];
            return;
        }

        $this->usulan_pegawais_approved = UsulanPegawai::where('usulan_id', $this->perencanaan->usulan_id)
            ->where('status', 'approved')
            ->with('kepegawaian')
            ->get()
            ->toArray();
    }

    public function addInput($i): void
    {
        $this->i = $i + 1;
        $this->inputs[$this->i] = [
            'persuratan_kategori_id' => '',
            'nama_surat' => '',
            'file_pdf' => null,
            'tanggal_upload' => date('Y-m-d'),
            'perihal' => '',
            'jenis_anggaran' => 'BPMP',
        ];
    }

    public function removeInput($i): void
    {
        unset($this->inputs[$i]);
    }

    public function submit()
    {
        $this->validate([
            'inputs.*.persuratan_kategori_id' => 'required|exists:persuratan_kategoris,id',
            'inputs.*.nama_surat' => 'required|string|max:255',
            'inputs.*.file_pdf' => 'required|file|mimes:pdf|max:2048',
            'inputs.*.tanggal_upload' => 'required|date',
            'inputs.*.jenis_anggaran' => 'required|in:BPMP,LUAR BPMP,GABUNGAN',
        ], [
            'inputs.*.persuratan_kategori_id.required' => 'Kategori surat wajib dipilih.',
            'inputs.*.nama_surat.required' => 'Nama surat wajib diisi.',
            'inputs.*.file_pdf.required' => 'File PDF wajib diunggah.',
            'inputs.*.file_pdf.mimes' => 'File harus format PDF.',
        ]);

        $pegawaiIds = array_column($this->usulan_pegawais_approved, 'pegawai_id');

        if (empty($pegawaiIds)) {
            session()->flash('error', 'Tidak ada pegawai yang disetujui untuk usulan ini.');
            return;
        }

        DB::transaction(function () use ($pegawaiIds) {
            foreach ($this->inputs as $value) {
                // 1. Upload File
                $path = $value['file_pdf']->store('persuratans', 'public');

                // 2. Simpan Data Surat (Hanya sekali per input dokumen)
                $surat = Persuratan::create([
                    'perencanaan_id'         => $this->perencanaan->id,
                    'usulan_id'              => $this->perencanaan->usulan_id,
                    'persuratan_kategori_id' => $value['persuratan_kategori_id'],
                    'nama_surat'             => $value['nama_surat'],
                    'file_pdf'               => $path,
                    'tanggal_upload'         => $value['tanggal_upload'],
                    'perihal'                => $value['perihal'],
                    'jenis_anggaran'         => $value['jenis_anggaran'],
                ]);

                // 3. Hubungkan ke banyak pegawai melalui tabel pivot
                // Ini akan mengisi tabel 'persuratan_pegawai'
                $surat->pegawais()->attach($pegawaiIds);
            }
        });

        session()->flash('success', 'Dokumen surat berhasil disimpan.');
        return redirect()->route('persuratans.index');
    }

    public function render()
    {
        return view('livewire.persuratans.persuratans-form', [
            'kategoris' => PersuratanKategori::all()
        ]);
    }
}