<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\BuktiPengeluaran;
use App\Models\PengajuanPencairan;
use App\Models\Perencanaan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PengajuanPencairanForm extends Component
{
    public $pengajuan_id;

    public $pegawai_id;

    public $perencanaan_id;

    public $nomor_surat;

    public $tanggal_pengajuan;

    public $uang_harian_nominal = 0;

    public $jumlah_hari = 0;

    public $uang_harian_total = 0;

    public $total_nominal = 0;

    public $perencanaanList = [];

    public $buktiList = [];

    public $selectedBukti = [];

    public function mount($id = null)
    {
        $this->perencanaanList = Perencanaan::orderBy('nama_komponen')->get();

        $pegawai = Auth::user()->kepegawaian;
        $this->pegawai_id = $pegawai->id ?? null;

        if ($id) {
            $this->pengajuan_id = $id;
            $pengajuan = PengajuanPencairan::with('buktiPengeluarans')->findOrFail($id);

            $this->perencanaan_id = $pengajuan->perencanaan_id;
            $this->nomor_surat = $pengajuan->nomor_surat;
            $this->tanggal_pengajuan = $pengajuan->tanggal_pengajuan->format('Y-m-d');
            $this->uang_harian_nominal = $pengajuan->uang_harian_nominal;
            $this->jumlah_hari = $pengajuan->jumlah_hari;
            $this->uang_harian_total = $pengajuan->uang_harian_total;
            $this->selectedBukti = $pengajuan->buktiPengeluarans->pluck('id')->toArray();

            $this->loadBukti();
        }
    }

    public function updatedPerencanaanId()
    {
        $this->loadBukti();
        $this->selectedBukti = [];
        $this->calculateTotal();
    }

    public function loadBukti()
    {
        if ($this->perencanaan_id && $this->pegawai_id) {
            $this->buktiList = BuktiPengeluaran::where('perencanaan_id', $this->perencanaan_id)
                ->where('pegawai_id', $this->pegawai_id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $this->buktiList = [];
        }
    }

    public function updatedSelectedBukti()
    {
        $this->calculateTotal();
    }

    public function updatedUangHarianNominal()
    {
        $this->calculateTotal();
    }

    public function updatedJumlahHari()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->uang_harian_total = $this->uang_harian_nominal * $this->jumlah_hari;

        $buktiTotal = BuktiPengeluaran::whereIn('id', $this->selectedBukti)->sum('nominal');

        $this->total_nominal = $buktiTotal + $this->uang_harian_total;
    }

    public function rules()
    {
        return [
            'perencanaan_id' => 'required|exists:perencanaans,id',
            'nomor_surat' => 'nullable|string|max:255',
            'tanggal_pengajuan' => 'required|date',
            'uang_harian_nominal' => 'required|numeric|min:0',
            'jumlah_hari' => 'required|integer|min:0',
            'selectedBukti' => 'required|array|min:1',
            'selectedBukti.*' => 'exists:bukti_pengeluarans,id',
        ];
    }

    public function submit()
    {
        $this->validate();

        $buktiTotal = BuktiPengeluaran::whereIn('id', $this->selectedBukti)->sum('nominal');
        $this->uang_harian_total = $this->uang_harian_nominal * $this->jumlah_hari;
        $this->total_nominal = $buktiTotal + $this->uang_harian_total;

        $data = [
            'pegawai_id' => $this->pegawai_id,
            'perencanaan_id' => $this->perencanaan_id,
            'nomor_surat' => $this->nomor_surat,
            'tanggal_pengajuan' => $this->tanggal_pengajuan,
            'uang_harian_nominal' => $this->uang_harian_nominal,
            'jumlah_hari' => $this->jumlah_hari,
            'uang_harian_total' => $this->uang_harian_total,
            'total_nominal' => $this->total_nominal,
            'status' => 'pending',
        ];

        if ($this->pengajuan_id) {
            $pengajuan = PengajuanPencairan::findOrFail($this->pengajuan_id);
            $pengajuan->update($data);
            $pengajuan->buktiPengeluarans()->sync($this->selectedBukti);
            session()->flash('success', 'Pengajuan berhasil diupdate.');
        } else {
            $pengajuan = PengajuanPencairan::create($data);
            $pengajuan->buktiPengeluarans()->attach($this->selectedBukti);
            session()->flash('success', 'Pengajuan berhasil dibuat.');
        }

        return redirect()->route('keuangan.pengajuan-pencairans.index');
    }

    public function render()
    {
        return view('livewire.pengajuan-pencairan-form')
            ->layout('layouts.app');
    }
}
