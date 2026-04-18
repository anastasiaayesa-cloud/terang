<?php

namespace App\Livewire\LaporanKegiatans;

use App\Models\Kepegawaian;
use App\Models\LaporanKegiatan;
use App\Models\Perencanaan;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class LaporanKegiatanForm extends Component
{
    use WithFileUploads;

    public $laporanKegiatan;

    public $pegawaiList = [];

    public $perencanaanList = [];

    public $pegawai_id;

    public $perencanaan_id;

    public $judul_laporan;

    public $deskripsi_kegiatan;

    public $tanggal_mulai;

    public $tanggal_selesai;

    public $lokasi_kegiatan;

    public $file_laporan;

    public function mount($laporanKegiatan = null)
    {
        $this->pegawaiList = Kepegawaian::orderBy('nama')->get();
        $this->perencanaanList = Perencanaan::orderBy('nama_komponen')->get();

        // INISIALISASI DEFAULT (PENTING)
        $this->pegawai_id = '';
        $this->perencanaan_id = '';

        if ($laporanKegiatan) {
            $this->laporanKegiatan = LaporanKegiatan::findOrFail($laporanKegiatan);
            $this->fill($this->laporanKegiatan->only([
                'pegawai_id',
                'perencanaan_id',
                'judul_laporan',
                'deskripsi_kegiatan',
                'tanggal_mulai',
                'tanggal_selesai',
                'lokasi_kegiatan',
            ]));
        }
    }

    public function rules()
    {
        $rules = [
            'pegawai_id' => 'required|exists:kepegawaians,id',
            'perencanaan_id' => 'required|exists:perencanaans,id',
            'judul_laporan' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi_kegiatan' => 'required|string|max:255',
        ];

        if ($this->file_laporan) {
            $rules['file_laporan'] = 'required|file|mimes:pdf|max:5120';
        } elseif (! $this->laporanKegiatan) {
            $rules['file_laporan'] = 'required|file|mimes:pdf|max:5120';
        }

        return $rules;
    }

    public function submit()
    {
        $data = $this->validate();

        if ($this->file_laporan) {
            $path = $this->file_laporan->store('laporan-kegiatan', 'public');

            if ($this->laporanKegiatan && $this->laporanKegiatan->file_laporan) {
                Storage::disk('public')->delete($this->laporanKegiatan->file_laporan);
            }

            $data['file_laporan'] = $path;
        }

        if ($this->laporanKegiatan) {
            $this->laporanKegiatan->update($data);
            session()->flash('success', 'Laporan berhasil diupdate.');
        } else {
            $data['status'] = 'pending';
            LaporanKegiatan::create($data);
            session()->flash('success', 'Laporan berhasil ditambahkan.');
        }

        return redirect()->route('laporan-kegiatans.index');
    }

    public function delete()
    {
        if ($this->laporanKegiatan) {
            if ($this->laporanKegiatan->file_laporan) {
                Storage::disk('public')->delete($this->laporanKegiatan->file_laporan);
            }
            $this->laporanKegiatan->delete();
            session()->flash('success', 'Laporan berhasil dihapus.');
        }

        return redirect()->route('laporan-kegiatans.index');
    }

    public function render()
    {
        return view('livewire.laporan-kegiatans.laporan-kegiatan-form')
            ->layout('layouts.app');
    }
}
