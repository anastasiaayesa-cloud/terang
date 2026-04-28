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

    // Properti Utama
    public $perencanaan_id = '';
    public $usulan_id = ''; 
    public $pegawai_id = '';

    public $bukti_id;
    public $files = [];
    
    // TAMBAHKAN KEMBALI PROPERTI INI AGAR BLADE TIDAK ERROR
    public $existingFiles = [];
    public $keepFiles = [];
    
    public $perencanaanList = [];

    public function mount($bukti_id = null)
    {
        $this->perencanaanList = Perencanaan::orderBy('nama_komponen', 'asc')->get();
        
        $urlUsulanId = request()->query('usulan_id');

        if ($bukti_id) {
            $this->bukti_id = $bukti_id;
        } 
        elseif ($urlUsulanId) {
            $this->usulan_id = $urlUsulanId;
            $perencanaan = Perencanaan::where('usulan_id', $urlUsulanId)->first();
            if ($perencanaan) {
                $this->perencanaan_id = $perencanaan->id; 
            }
            // HAPUS ATAU KOMENTAR BARIS INI:
            // $this->pegawai_id = Auth::user()->kepegawaian?->id;
            
            $this->addFile();
        } 
        else {
            // HAPUS ATAU KOMENTAR BARIS INI:
            // $this->pegawai_id = Auth::user()->kepegawaian?->id;
            
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
        $rules = [
            'perencanaan_id' => 'required|exists:perencanaans,id',
            'usulan_id'      => 'nullable', 
            'pegawai_id'     => 'nullable',
        ];

        foreach ($this->files as $index => $file) {
            if (!empty($file['file'])) {
                $rules["files.{$index}.file"] = 'file|mimes:pdf,jpg,jpeg,png|max:5120';
            }
        }

        $this->validate($rules);

        $filesToSubmit = array_filter($this->files, function ($file) {
            return !empty($file['file']) && !empty($file['tipe_bukti']) && !empty($file['nominal']);
        });

        if (empty($filesToSubmit)) {
            session()->flash('error', 'Silakan lengkapi data file.');
            return;
        }

        foreach ($filesToSubmit as $fileData) {
            $file = $fileData['file'];
            $fileName = time().'_'.uniqid().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('bukti-pengeluaran', $fileName, 'public');

            BuktiPengeluaran::create([
                'perencanaan_id' => $this->perencanaan_id,
                'usulan_id'      => $this->usulan_id,
                'pegawai_id'     => $this->pegawai_id ?: null,
                'tipe_bukti'     => $fileData['tipe_bukti'],
                'file_path'      => $filePath,
                'file_name'      => $file->getClientOriginalName(),
                'file_type'      => $file->getMimeType(),
                'file_size'      => $file->getSize(),
                'nominal'        => $fileData['nominal'],
                'keterangan'     => $fileData['keterangan'] ?? null,
                'tanggal_bukti'  => $fileData['tanggal_bukti'] ?? null,
                'status'         => 'pending',
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