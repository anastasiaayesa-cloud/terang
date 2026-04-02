<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\BuktiPengeluaran;
use App\Models\Perencanaan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class BuktiPengeluaransUpload extends Component
{
    use WithFileUploads;

    public $perencanaan_id;

    public $bukti_id; // untuk edit mode

    public $files = [];

    public $existingFiles = [];

    public $keepFiles = [];

    public $perencanaanList = [];

    public $perencanaan;

    public function mount($bukti_id = null)
    {
        // Load all perencanaans for dropdown
        $this->perencanaanList = Perencanaan::orderBy('nama_komponen', 'asc')->get();

        if ($bukti_id) {
            // Edit mode: load existing bukti
            $bukti = BuktiPengeluaran::findOrFail($bukti_id);

            $this->bukti_id = $bukti_id;
            $this->perencanaan_id = $bukti->perencanaan_id;
            $this->perencanaan = $bukti->perencanaan;

            $this->existingFiles = [$bukti];
            $this->keepFiles = [$bukti->id => true];
        }
    }

    public function addFile()
    {
        $index = count($this->files);
        $this->files[] = [
            'index' => $index,
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

        // Re-index
        foreach ($this->files as $i => $file) {
            $this->files[$i]['index'] = $i;
        }
    }

    public function toggleKeepFile($buktiId)
    {
        $this->keepFiles[$buktiId] = ! $this->keepFiles[$buktiId];
    }

    public function removeExistingFile($buktiId)
    {
        $this->existingFiles = array_filter(
            $this->existingFiles,
            fn ($bukti) => $bukti->id != $buktiId
        );
        unset($this->keepFiles[$buktiId]);
    }

    public function submit()
    {
        // Validasi perencanaan wajib
        $this->validate([
            'perencanaan_id' => 'required|exists:perencanaans,id',
        ]);

        // Validasi files baru
        $rules = [];
        foreach ($this->files as $index => $file) {
            $rules["files.{$index}.file"] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
            $rules["files.{$index}.tipe_bukti"] = 'required|in:tiket_pesawat,tiket_kapal,tiket_kereta,tiket_taxi,tiket_hotel,bukti_lainnya';
            $rules["files.{$index}.nominal"] = 'required|numeric|min:0';
            $rules["files.{$index}.tanggal_bukti"] = 'nullable|date';
            $rules["files.{$index}.keterangan"] = 'nullable|string|max:500';
        }

        $this->validate($rules);

        // Hapus file yang tidak di-keep (edit mode)
        if ($this->bukti_id) {
            foreach ($this->existingFiles as $bukti) {
                if (empty($this->keepFiles[$bukti->id])) {
                    // Hapus file dari storage
                    if (Storage::disk('public')->exists($bukti->file_path)) {
                        Storage::disk('public')->delete($bukti->file_path);
                    }
                    $bukti->delete();
                } else {
                    // Update data jika perlu
                    $bukti->update([
                        'tipe_bukti' => $this->existingFiles[array_search($bukti, $this->existingFiles)]->tipe_bukti,
                        'nominal' => $this->existingFiles[array_search($bukti, $this->existingFiles)]->nominal,
                        'keterangan' => $this->existingFiles[array_search($bukti, $this->existingFiles)]->keterangan,
                        'tanggal_bukti' => $this->existingFiles[array_search($bukti, $this->existingFiles)]->tanggal_bukti,
                    ]);
                }
            }
        }

        // Upload files baru
        foreach ($this->files as $fileData) {
            if (empty($fileData['file'])) {
                continue;
            }

            $file = $fileData['file'];
            $fileName = time().'_'.uniqid().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('bukti-pengeluaran', $fileName, 'public');

            BuktiPengeluaran::create([
                'perencanaan_id' => $this->perencanaan_id,
                'pegawai_id' => Auth::user()->kepegawaian->id ?? null,
                'tipe_bukti' => $fileData['tipe_bukti'],
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'nominal' => $fileData['nominal'],
                'keterangan' => $fileData['keterangan'] ?? null,
                'tanggal_bukti' => $fileData['tanggal_bukti'] ?? null,
            ]);
        }

        session()->flash('success', 'Bukti pengeluaran berhasil diupload.');

        return redirect()->route('bukti-pengeluarans.index');
    }

    public function render()
    {
        return view('livewire.bukti-pengeluarans.bukti-pengeluarans-upload')
            ->layout('layouts.app');
    }
}
