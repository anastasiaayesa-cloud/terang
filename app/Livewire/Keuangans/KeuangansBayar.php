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

    public $manualPayments = [];

    public $allPayments = [];

    public $showModal = false;

    public $showModalManual = false;

    public $selectedBuktiId = null;

    public $nominalBayar = '';

    public $alasan = '';

    public $manualPerincian = '';

    public $manualNominal = '';

    public $manualJumlah = 1;

    public $manualJenis = 'Biaya Perjalanan Dinas';

    public $manualSatuan = '';

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

        $this->manualPayments = Keuangan::where('usulan_id', $this->usulan_id)
            ->where('pegawai_id', $this->pegawai_id)
            ->whereNull('bukti_pengeluaran_id')
            ->get();

        // Combine both into allPayments
        $combined = collect();

        // Add bukti pengeluarans
        foreach ($this->buktiPengeluarans as $bukti) {
            $combined->push([
                'jenis' => 'bukti',
                'id' => $bukti->id,
                'keterangan' => $bukti->keterangan,
                'nominal' => $bukti->nominal,
                'uang_dibayarkan' => $bukti->keuangan ? $bukti->keuangan->uang_dibayarkan : null,
                'jumlah' => 1,
                'status' => $bukti->keuangan ? $bukti->keuangan->status : 'pending',
                'keuangan' => $bukti->keuangan,
            ]);
        }

        // Add manual payments
        foreach ($this->manualPayments as $manual) {
            $combined->push([
                'jenis' => 'manual',
                'id' => $manual->id,
                'keterangan' => $manual->perincian_bayar,
                'nominal' => $manual->nominal,
                'uang_dibayarkan' => $manual->uang_dibayarkan,
                'jumlah' => $manual->jumlah,
                'status' => $manual->status,
                'keuangan' => $manual,
            ]);
        }

        $this->allPayments = $combined;
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

    public function openModalManual()
    {
        $this->resetManualFields();
        $this->showModalManual = true;
    }

    public function resetManualFields()
    {
        $this->manualPerincian = '';
        $this->manualNominal = '';
        $this->manualJumlah = 1;
        $this->manualSatuan = '';
        $this->manualJenis = 'Biaya Perjalanan Dinas';
    }

    public function closeModalManual()
    {
        $this->showModalManual = false;
    }

    public function saveManual()
    {
        $this->validate([
            'manualPerincian' => 'required|string',
            'manualNominal' => 'required|numeric|min:0',
            'manualJumlah' => 'required|integer|min:1',
            'manualSatuan' => 'nullable|in:kali,hr',
        ]);

        $nominalManual = (float) $this->manualNominal;
        $jumlahManual = (int) $this->manualJumlah;
        $totalManual = $nominalManual * $jumlahManual;

        Keuangan::create([
            'usulan_id' => $this->usulan_id,
            'pegawai_id' => $this->pegawai_id,
            'bukti_pengeluaran_id' => null,
            'perincian_bayar' => $this->manualPerincian,
            'nominal' => $nominalManual,
            'jumlah' => $jumlahManual,
            'satuan' => $this->manualSatuan ?: null,
            'total' => $totalManual,
            'uang_dibayarkan' => $totalManual,
            'jenis' => $this->manualJenis,
            'status' => 'full',
        ]);

        $this->closeModalManual();
        $this->loadData();
        session()->flash('success', 'Pembayaran manual berhasil ditambahkan.');
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

    public function render()
    {
        return view('livewire.keuangans.keuangan-bayar')
            ->layout('layouts.app');
    }
}
