<?php

declare(strict_types=1);

namespace App\Livewire\Keuangans;

use App\Models\BuktiPengeluaran;
use App\Models\Keuangan;
use App\Models\Usulan;
use App\Models\UsulanPegawai;
use Livewire\Component;

class KeuangansBayar extends Component
{
    public $usulan_id;

    public $pegawai_id;

    public $usulan;

    public $pegawai;

    public $buktiPengeluarans = [];

    public $showModal = false;

    public $selectedBuktiId = null;

    public $nominalBayar = '';

    public $alasan = '';

    public function mount($usulan_id, $pegawai_id)
    {
        $this->usulan_id = $usulan_id;
        $this->pegawai_id = $pegawai_id;
        $this->loadData();
    }

    public function loadData()
    {
        $this->usulan = Usulan::find($this->usulan_id);
        $this->pegawai = UsulanPegawai::where('usulan_id', $this->usulan_id)
            ->where('pegawai_id', $this->pegawai_id)
            ->with('kepegawaian')
            ->first();

        $this->buktiPengeluarans = BuktiPengeluaran::where('usulan_id', $this->usulan_id)
            ->where('pegawai_id', $this->pegawai_id)
            ->with('keuangan')
            ->get();
    }

    public function openModal($buktiId)
    {
        $this->selectedBuktiId = $buktiId;
        $this->showModal = true;
        $this->resetFields();
    }

    public function resetFields()
    {
        $this->nominalBayar = '';
        $this->alasan = '';
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedBuktiId = null;
    }

    public function bayarfull($buktiId)
    {
        $bukti = BuktiPengeluaran::find($buktiId);
        $nominalBukti = $bukti->nominal ?? 0;
        $keterangan = $bukti->keterangan ?? '';

        $keuangan = Keuangan::where('bukti_pengeluaran_id', $buktiId)->first();

        if ($keuangan) {
            $keuangan->uang_dibayarkan = $nominalBukti;
            $keuangan->total = $nominalBukti;
            $keuangan->status = 'full';
            $keuangan->alasan_penolakan = null;
            $keuangan->save();
        } else {
            Keuangan::create([
                'usulan_id' => $this->usulan_id,
                'pegawai_id' => $this->pegawai_id,
                'bukti_pengeluaran_id' => $buktiId,
                'perincian_bayar' => $keterangan,
                'nominal' => $nominalBukti,
                'uang_dibayarkan' => $nominalBukti,
                'total' => $nominalBukti,
                'jumlah' => 1,
                'jenis' => 'Biaya Perjalanan Dinas',
                'status' => 'full',
            ]);
        }

        $this->loadData();
        session()->flash('success', 'Pembayaran bukti berhasil dilakukan.');
    }

    public function bayarsebagian()
    {
        $this->validate([
            'nominalBayar' => 'required|numeric|min:0',
            'alasan' => 'required|string',
        ]);

        $bukti = BuktiPengeluaran::find($this->selectedBuktiId);
        $nominalBukti = $bukti->nominal ?? 0;
        $nominalBayar = (float) $this->nominalBayar;
        $keterangan = $bukti->keterangan ?? '';

        $keuangan = Keuangan::where('bukti_pengeluaran_id', $this->selectedBuktiId)->first();

        if ($keuangan) {
            $keuangan->uang_dibayarkan = $nominalBayar;
            $keuangan->total = $nominalBukti;
            $keuangan->status = 'sebagian';
            $keuangan->alasan_penolakan = $this->alasan;
            $keuangan->save();
        } else {
            Keuangan::create([
                'usulan_id' => $this->usulan_id,
                'pegawai_id' => $this->pegawai_id,
                'bukti_pengeluaran_id' => $this->selectedBuktiId,
                'perincian_bayar' => $keterangan,
                'nominal' => $nominalBukti,
                'uang_dibayarkan' => $nominalBayar,
                'total' => $nominalBukti,
                'jumlah' => 1,
                'jenis' => 'Biaya Perjalanan Dinas',
                'status' => 'sebagian',
                'alasan_penolakan' => $this->alasan,
            ]);
        }

        $this->closeModal();
        $this->loadData();
        session()->flash('success', 'Pembayaran sebagian berhasil dilakukan.');
    }

    public function getStatusForBukti($bukti)
    {
        if ($bukti->keuangan) {
            return $bukti->keuangan->status;
        }

        return 'pending';
    }

    public function getUangDibayarkanForBukti($bukti)
    {
        if ($bukti->keuangan) {
            return $bukti->keuangan->uang_dibayarkan;
        }

        return null;
    }

    public function render()
    {
        return view('livewire.keuangans.keuangan-bayar')
            ->layout('layouts.app');
    }
}
