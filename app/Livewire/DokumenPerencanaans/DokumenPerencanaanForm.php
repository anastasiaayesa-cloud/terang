<?php

namespace App\Livewire\DokumenPerencanaans;

use App\Models\DokumenPerencanaan;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class DokumenPerencanaanForm extends Component
{
    use WithFileUploads;

    public $dokumenperencanaan_id;

    public $nama;

    public $file_pdf;

    public $tanggal;

    public $existing_pdf;

    public function mount($dokumenperencanaan_id = null)
    {
        if ($dokumenperencanaan_id) {

            $this->dokumenperencanaan_id = $dokumenperencanaan_id;
            $dokumen = DokumenPerencanaan::findOrFail($dokumenperencanaan_id);

            // Isi data lama
            $this->nama = $dokumen->nama;
            $this->tanggal = $dokumen->tanggal;

            // Simpan path file lama (untuk ditampilkan / tidak dihapus)
            $this->existing_pdf = $dokumen->file_pdf;
        }
    }

    public function rules()
    {
        return [
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date',

            // Jika EDIT, file_pdf tidak wajib
            'file_pdf' => $this->dokumenperencanaan_id
                ? 'nullable|mimes:pdf'
                : 'required|mimes:pdf',
        ];
    }

    public function submit()
    {
        $this->validate();

        // Upload file baru jika ada
        if ($this->file_pdf) {
            $pdfPath = $this->file_pdf->store('dokumenperencanaan', 'public');
        } else {
            // Jika tidak upload baru saat edit, tetap gunakan file lama
            $pdfPath = $this->existing_pdf;
        }

        // EDIT
        if ($this->dokumenperencanaan_id) {

            $dokumen = DokumenPerencanaan::findOrFail($this->dokumenperencanaan_id);

            // Hapus file lama jika upload baru
            if ($this->file_pdf && $dokumen->file_pdf) {
                Storage::disk('public')->delete($dokumen->file_pdf);
            }

            $dokumen->update([
                'nama' => $this->nama,
                'file_pdf' => $pdfPath,
                'tanggal' => $this->tanggal,
            ]);

            session()->flash('success', 'Dokumen berhasil diperbarui.');

            return redirect()->route('dokumen-perencanaans.index');
        }

        // CREATE BARU
        DokumenPerencanaan::create([
            'nama' => $this->nama,
            'file_pdf' => $pdfPath,
            'tanggal' => $this->tanggal,
        ]);

        session()->flash('success', 'Dokumen baru berhasil ditambahkan.');

        return redirect()->route('dokumen-perencanaans.index');
    }

    public function render()
    {
        return view('livewire.dokumen-perencanaans.dokumen-perencanaan-form')
            ->layout('layouts.app');
    }
}
