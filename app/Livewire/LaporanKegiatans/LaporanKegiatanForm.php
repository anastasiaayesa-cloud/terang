<?php

namespace App\Livewire\LaporanKegiatans;

use App\Models\Kepegawaian;
use App\Models\LaporanKegiatan;
use App\Models\Usulan;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class LaporanKegiatanForm extends Component
{
    use WithFileUploads;

    public $laporanKegiatan;
    
    // List untuk Dropdown (PerencanaanList dihapus karena disembunyikan)
    public $pegawaiList = [];
    public $usulanList = [];

    // Properti Form
    public $pegawai_id;
    public $usulan_id;
    public $perencanaan_id = null; // Default null karena input disembunyikan
    public $judul_laporan;
    public $deskripsi_kegiatan;
    public $tanggal_mulai;
    public $tanggal_selesai;
    public $lokasi_kegiatan;
    public $file_laporan;

    /**
     * Parameter usulanId dan pegawaiId ditangkap dari Route
     */
    public function mount($laporanKegiatan = null, $usulanId = null, $pegawaiId = null)
    {
        // 1. Load Data untuk Dropdown (Query Perencanaan dihapus untuk optimasi)
        $this->pegawaiList = Kepegawaian::orderBy('nama')->get();
        $this->usulanList = Usulan::orderBy('nama_kegiatan')->get();

        // 2. Jika Mode Edit (Ada ID Laporan)
        if ($laporanKegiatan) {
            $this->laporanKegiatan = LaporanKegiatan::findOrFail($laporanKegiatan);
            $this->fill($this->laporanKegiatan->only([
                'pegawai_id',
                'usulan_id',
                'perencanaan_id',
                'judul_laporan',
                'deskripsi_kegiatan',
                'tanggal_mulai',
                'tanggal_selesai',
                'lokasi_kegiatan',
            ]));
        } else {
            // 3. Jika Mode Create (Auto-fill dari URL)
            $this->usulan_id = $usulanId;
            $this->pegawai_id = $pegawaiId;
            
            // Auto-fill Judul, Lokasi, dan Tanggal berdasarkan data Usulan di Database
            if ($usulanId) {
                $dataUsulan = Usulan::find($usulanId);
                if ($dataUsulan) {
                    // Logic untuk munculnya judul otomatis
                    $this->judul_laporan = "Laporan Kegiatan: " . $dataUsulan->nama_kegiatan;
                    $this->lokasi_kegiatan = $dataUsulan->lokasi_kegiatan;
                    $this->tanggal_mulai = $dataUsulan->tanggal_kegiatan;
                }
            }
        }
    }

    public function rules()
    {
        return [
            'pegawai_id' => 'required|exists:kepegawaians,id',
            'usulan_id' => 'nullable|exists:usulans,id',
            'perencanaan_id' => 'nullable', // Tetap nullable di validasi
            'judul_laporan' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi_kegiatan' => 'required|string|max:255',
            'file_laporan' => $this->laporanKegiatan ? 'nullable|file|mimes:pdf|max:5120' : 'required|file|mimes:pdf|max:5120',
        ];
    }

    public function submit()
    {
        $data = $this->validate();

        // Proses Upload File Laporan
        if ($this->file_laporan) {
            $path = $this->file_laporan->store('laporan-kegiatan', 'public');

            // Hapus file lama jika ada (saat update)
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