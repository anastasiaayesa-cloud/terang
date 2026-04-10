<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Kepegawaian;
use App\Models\PaguHotel;
use App\Models\Perencanaan;
use App\Models\UsulanPembayaran;
use Livewire\Component;

class UsulanPembayaranForm extends Component
{
    public $usulan_id;

    public $perencanaan_id;

    public $pegawai_id;

    public $provinsi_tujuan;

    public $tanggal_mulai;

    public $tanggal_selesai;

    public $perencanaanList = [];

    public $pegawaiList = [];

    public $paguList = [];

    public $selectedPegawai = null;

    // Auto-calculated
    public $jumlah_malam = 0;

    public $golongan = '';

    public $golongan_label = '';

    public $tarif_hotel_sbm = 0;

    public $persen_klaim = 30;

    public $nominal_per_malam = 0;

    public $total_nominal = 0;

    public function mount($usulan_id = null)
    {
        $this->perencanaanList = Perencanaan::orderBy('nama_komponen')->get();
        $this->pegawaiList = Kepegawaian::with('pangkat')->orderBy('nama')->get();
        $this->paguList = PaguHotel::orderBy('provinsi')->get();

        if ($usulan_id) {
            $usulan = UsulanPembayaran::findOrFail($usulan_id);
            $this->usulan_id = $usulan_id;
            $this->perencanaan_id = $usulan->perencanaan_id;
            $this->pegawai_id = $usulan->pegawai_id;
            $this->provinsi_tujuan = $usulan->provinsi_tujuan;
            $this->tanggal_mulai = $usulan->tanggal_mulai->format('Y-m-d');
            $this->tanggal_selesai = $usulan->tanggal_selesai->format('Y-m-d');
            $this->persen_klaim = $usulan->persen_klaim;

            $this->calculate();
        }
    }

    public function updatedPegawaiId($value)
    {
        if ($value) {
            $this->selectedPegawai = $this->pegawaiList->firstWhere('id', $value);
        } else {
            $this->selectedPegawai = null;
        }
        $this->calculate();
    }

    public function updatedProvinsiTujuan($value)
    {
        $this->calculate();
    }

    public function updatedTanggalMulai($value)
    {
        $this->calculate();
    }

    public function updatedTanggalSelesai($value)
    {
        $this->calculate();
    }

    public function updatedPersenKlaim($value)
    {
        $this->calculate();
    }

    public function calculate()
    {
        // Hitung jumlah malam
        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            $mulai = new \DateTime($this->tanggal_mulai);
            $selesai = new \DateTime($this->tanggal_selesai);
            $diff = $mulai->diff($selesai);
            $this->jumlah_malam = max(0, (int) $diff->days);
        } else {
            $this->jumlah_malam = 0;
        }

        // Hitung golongan dari pangkat pegawai
        if ($this->pegawai_id) {
            $pegawai = $this->pegawaiList->firstWhere('id', $this->pegawai_id);
            if ($pegawai && $pegawai->pangkat_id) {
                $this->golongan = UsulanPembayaran::hitungGolonganDariPangkat($pegawai->pangkat_id);
                $this->golongan_label = match ($this->golongan) {
                    'eselon_i' => 'Eselon I',
                    'eselon_ii' => 'Eselon II',
                    'eselon_iii' => 'Eselon III / Gol IV',
                    'eselon_iv' => 'Eselon IV / Gol III, II, I',
                    default => '-',
                };
            }
        }

        // Hitung tarif dari pagu
        if ($this->provinsi_tujuan && $this->golongan) {
            $pagu = $this->paguList->firstWhere('provinsi', $this->provinsi_tujuan);
            if ($pagu) {
                $this->tarif_hotel_sbm = $pagu->getTarifByGolongan($this->golongan);
            }
        } else {
            $this->tarif_hotel_sbm = 0;
        }

        // Hitung nominal
        $this->nominal_per_malam = $this->tarif_hotel_sbm * ($this->persen_klaim / 100);
        $this->total_nominal = $this->nominal_per_malam * $this->jumlah_malam;
    }

    public function rules()
    {
        return [
            'perencanaan_id' => 'required|exists:perencanaans,id',
            'pegawai_id' => 'required|exists:kepegawaians,id',
            'provinsi_tujuan' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'persen_klaim' => 'required|numeric|min:0|max:100',
        ];
    }

    public function submit()
    {
        $this->validate();

        $data = [
            'perencanaan_id' => $this->perencanaan_id,
            'pegawai_id' => $this->pegawai_id,
            'provinsi_tujuan' => $this->provinsi_tujuan,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'jumlah_malam' => $this->jumlah_malam,
            'golongan' => $this->golongan,
            'tarif_hotel_sbm' => $this->tarif_hotel_sbm,
            'persen_klaim' => $this->persen_klaim,
            'nominal_per_malam' => $this->nominal_per_malam,
            'total_nominal' => $this->total_nominal,
        ];

        if ($this->usulan_id) {
            $usulan = UsulanPembayaran::findOrFail($this->usulan_id);
            $usulan->update($data);
            session()->flash('success', 'Usulan pembayaran berhasil diupdate.');
        } else {
            UsulanPembayaran::create($data);
            session()->flash('success', 'Usulan pembayaran berhasil ditambahkan.');
        }

        return redirect()->route('usulan-pembayarans.index');
    }

    public function render()
    {
        return view('livewire.usulan-pembayarans.usulan-pembayaran-form')
            ->layout('layouts.app');
    }
}
