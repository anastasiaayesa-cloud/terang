<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\BuktiPengeluaran;
use App\Models\Perencanaan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class BuktiPengeluaransUpload extends Component
{
    use WithFileUploads;

    public $perencanaan_id = ''; // Inisialisasi string kosong

    public $bukti_id;

    public $files = [];

    public $existingFiles = [];

    public $keepFiles = [];

    public $perencanaanList = [];

    public $pegawai_id = '';

    public function mount($bukti_id = null)
    {
        $this->perencanaanList = Perencanaan::orderBy('nama_komponen', 'asc')->get();

        if ($bukti_id) {
            $bukti = BuktiPengeluaran::findOrFail($bukti_id);
            $this->bukti_id = $bukti_id;
            $this->perencanaan_id = $bukti->perencanaan_id;
            $this->existingFiles = [$bukti];
            $this->keepFiles = [$bukti->id => true];
        } else {
            // Tambahkan satu form kosong di awal jika mode create
            $this->addFile();
        }
    }

    public function addFile()
    {
        $this->files[] = [
            'file' => null,
            'tipe_bukti' => '',
            'nominal' => '',
            'keterangan' => '',
            'tanggal_bukti' => date('Y-m-d'),
        ];
    }

    public function removeFile($index)
    {
        unset($this->files[$index]);
        $this->files = array_values($this->files);
    }

    public function submit()
    {
        // 1. Validasi Utama
        $rules = [
            'perencanaan_id' => 'required|exists:perencanaans,id',
        ];

        // 2. Validasi Array Files - Hanya wajib jika ada file
        foreach ($this->files as $index => $file) {
            if (! empty($file['file'])) {
                $rules["files.{$index}.file"] = 'file|mimes:pdf,jpg,jpeg,png|max:5120';
            }
            if (! empty($file['tipe_bukti'])) {
                $rules["files.{$index}.tipe_bukti"] = 'string';
            }
            if (! empty($file['nominal'])) {
                $rules["files.{$index}.nominal"] = 'numeric|min:0';
            }
        }

        $this->validate($rules);

        // 3. Filter hanya file yang akan disubmit
        $filesToSubmit = array_filter($this->files, function ($file) {
            return ! empty($file['file']) && ! empty($file['tipe_bukti']) && ! empty($file['nominal']);
        });

        // Jika tidak ada file yang lengkap, jangan submit
        if (empty($filesToSubmit)) {
            session()->flash('error', 'Silakan lengkapi data file (file, tipe bukti, dan nominal) sebelum upload.');

            return;
        }

        // 4. Cek Pegawai ID (DIUBAH)
        $pegawai_id = Auth::user()->kepegawaian?->id;

        // Jika kamu ingin mengizinkan submit tanpa pegawai,
        // matikan (comment) blok if di bawah ini:
        /* if (! $pegawai_id) {
            session()->flash('error', 'User Anda tidak terhubung dengan data Pegawai. Silakan hubungi admin.');
            return;
        }
        */

        // 5. Proses Simpan
        foreach ($filesToSubmit as $fileData) {
            $file = $fileData['file'];
            $fileName = time().'_'.uniqid().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('bukti-pengeluaran', $fileName, 'public');

            // BAGIAN INI YANG HARUS DIPERHATIKAN:
            BuktiPengeluaran::create([
                'perencanaan_id' => $this->perencanaan_id,
                'pegawai_id' => $pegawai_id, // Variabel ini sekarang bisa bernilai null
                'tipe_bukti' => $fileData['tipe_bukti'],
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'nominal' => $fileData['nominal'],
                'keterangan' => $fileData['keterangan'] ?? null,
                'tanggal_bukti' => $fileData['tanggal_bukti'] ?? null,
                'status' => 'pending',
            ]);
        }

        session()->flash('success', 'Bukti pengeluaran berhasil diunggah.');

        return redirect()->route('bukti-pengeluarans.index');
    }

    public function render()
    {
        return view('livewire.bukti-pengeluarans.bukti-pengeluarans-upload')
            ->layout('layouts.app');
    }
}
