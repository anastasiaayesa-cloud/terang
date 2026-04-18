<?php

declare(strict_types=1);

namespace App\Livewire\Persuratans;

use App\Models\Persuratan;
use App\Models\UsulanPegawai;
use Livewire\Component;
use Livewire\WithFileUploads;

class PersuratansForm extends Component
{
    use WithFileUploads;

    public $usulan_id;

    public $daftar_pegawai = [];

    // Properti untuk menampung banyak baris surat
    public $inputs = [];

    public $i = 0;

    public function mount(): void
    {
        // Cara paling aman: Tangkap dari query string dan paksa menjadi integer
        $idFromUrl = request()->query('usulan_id');

        if (! $idFromUrl) {
            // Jika ID tidak ada, kembalikan ke index agar tidak terjadi error database
            redirect()->route('persuratans.index');

            return;
        }

        $this->usulan_id = (int) $idFromUrl;

        // Ambil daftar pegawai menggunakan ID yang sudah divalidasi
        $this->daftar_pegawai = UsulanPegawai::where('usulan_id', $this->usulan_id)
            ->where('status', 'approved')
            ->with('kepegawaian')
            ->get();

        $this->addInput(0);
    }

    // Fungsi menambah baris input baru
    public function addInput($i)
    {
        $this->i = $i + 1;
        $this->inputs[$this->i] = [
            'nama_surat' => '',
            'file_pdf' => null,
            'tanggal_upload' => date('Y-m-d'),
            'perihal' => '',
            'jenis_anggaran' => 'BPMP',
        ];
    }

    // Fungsi menghapus baris input
    public function removeInput($i)
    {
        unset($this->inputs[$i]);
    }

    public function submit()
    {
        // 1. Validasi array input
        $this->validate([
            'inputs.*.nama_surat' => 'required|string|max:255',
            'inputs.*.file_pdf' => 'required|file|mimes:pdf|max:2048',
            'inputs.*.tanggal_upload' => 'required|date',
            'inputs.*.jenis_anggaran' => 'required|in:BPMP,LUAR BPMP,GABUNGAN',
        ], [
            'inputs.*.nama_surat.required' => 'Nama surat wajib diisi.',
            'inputs.*.file_pdf.required' => 'File PDF wajib diunggah.',
            'inputs.*.file_pdf.mimes' => 'File harus format PDF.',
        ]);

        // 2. Ambil semua pegawai_id dari daftar_pegawai yang sudah di-load di mount()
        // Kita simpan ke dalam array agar bisa di-looping
        // PROTEKSI: Jika usulan_id tidak ada, jangan lanjutkan
        if (! $this->usulan_id) {
            session()->flash('error', 'ID Kegiatan tidak ditemukan. Silakan kembali ke halaman indeks.');

            return;
        }

        $pegawaiIds = $this->daftar_pegawai->pluck('pegawai_id');

        foreach ($this->inputs as $value) {
            $path = $value['file_pdf']->store('persuratans', 'public');

            foreach ($pegawaiIds as $idPegawai) {
                Persuratan::create([
                    'usulan_id' => $this->usulan_id, // Sekarang aman
                    'pegawai_id' => $idPegawai,
                    'nama_surat' => $value['nama_surat'],
                    'file_pdf' => $path,
                    'tanggal_upload' => $value['tanggal_upload'],
                    'perihal' => $value['perihal'] ?: '-',
                    'jenis_anggaran' => $value['jenis_anggaran'],
                ]);
            }
        }

        session()->flash('success', 'Dokumen surat berhasil disimpan.');

        return redirect()->route('persuratans.index');
    }

    public function render()
    {
        return view('livewire.persuratans.persuratans-form');
    }
}
