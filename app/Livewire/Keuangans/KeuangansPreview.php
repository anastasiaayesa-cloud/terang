<?php

declare(strict_types=1);

namespace App\Livewire\Keuangans;

use App\Models\BuktiPengeluaran;
use App\Models\Keuangan;
use App\Models\Usulan;
use App\Models\UsulanPegawai;
use Livewire\Component;

class KeuangansPreview extends Component
{
    public $usulan_id;

    public $pegawai_id;

    public $usulan;

    public $pegawai;

    public $payments = [];

    public $tanggal_kwitansi = '';

    public $maksud_perjalanan_dinas = '';

    public $alat_angkut = '';

    public $tempat_berangkat = '';

    public $tempat_tujuan = '';

    public $jenisOptions = [
        'Biaya Perjalanan Dinas',
        'Pengeluaran Rill',
        'Keduanya',
    ];

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

        $buktiPengeluarans = BuktiPengeluaran::where('usulan_id', $this->usulan_id)
            ->where('pegawai_id', $this->pegawai_id)
            ->with('keuangan')
            ->get();

        $manualPayments = Keuangan::where('usulan_id', $this->usulan_id)
            ->where('pegawai_id', $this->pegawai_id)
            ->whereNull('bukti_pengeluaran_id')
            ->get();

        $combined = collect();

        foreach ($buktiPengeluarans as $bukti) {
            $combined->push([
                'jenis' => 'bukti',
                'id' => $bukti->id,
                'keuangan_id' => $bukti->keuangan?->id,
                'perincian_bayar' => $bukti->keterangan,
                'jenis_pembayaran' => $bukti->keuangan?->jenis ?? 'Biaya Perjalanan Dinas',
                'nominal' => $bukti->nominal,
                'uang_dibayarkan' => $bukti->keuangan ? $bukti->keuangan->uang_dibayarkan : null,
                'jumlah' => 1,
                'status' => $bukti->keuangan ? $bukti->keuangan->status : 'pending',
            ]);
        }

        foreach ($manualPayments as $manual) {
            $combined->push([
                'jenis' => 'manual',
                'id' => $manual->id,
                'keuangan_id' => $manual->id,
                'perincian_bayar' => $manual->perincian_bayar,
                'jenis_pembayaran' => $manual->jenis,
                'nominal' => $manual->nominal,
                'uang_dibayarkan' => $manual->uang_dibayarkan,
                'jumlah' => $manual->jumlah,
                'satuan' => $manual->satuan,
                'status' => $manual->status,
            ]);
        }

        $this->payments = $combined->values()->all();

        $firstKeuangan = Keuangan::where('usulan_id', $this->usulan_id)
            ->where('pegawai_id', $this->pegawai_id)
            ->first();
        $this->tanggal_kwitansi = $firstKeuangan?->tanggal_kwitansi ?? '';
        $this->maksud_perjalanan_dinas = $firstKeuangan?->maksud_perjalanan_dinas ?? '';
        $this->alat_angkut = $firstKeuangan?->alat_angkut ?? '';
        $this->tempat_berangkat = $firstKeuangan?->tempat_berangkat ?? '';
        $this->tempat_tujuan = $firstKeuangan?->tempat_tujuan ?? '';

        return $this->payments;
    }

    public function simpanTanggalKwitansi()
    {
        if (empty($this->tanggal_kwitansi)) {
            return;
        }

        $keuangans = Keuangan::where('usulan_id', $this->usulan_id)
            ->where('pegawai_id', $this->pegawai_id)
            ->get();

        foreach ($keuangans as $keuangan) {
            $keuangan->tanggal_kwitansi = $this->tanggal_kwitansi;
            $keuangan->save();
        }

        session()->flash('success', 'Tanggal Kwitansi berhasil disimpan.');
    }

    public function saveAll()
    {
        $this->validate([
            'payments.*.perincian_bayar' => 'required|string',
            'payments.*.jenis_pembayaran' => 'required|string|in:Biaya Perjalanan Dinas,Pengeluaran Rill,Keduanya',
            'tanggal_kwitansi' => 'required|date',
        ], [
            'tanggal_kwitansi.required' => 'Tanggal Kwitansi wajib diisi.',
        ]);

        foreach ($this->payments as $payment) {
            if ($payment['jenis'] === 'bukti' && $payment['keuangan_id']) {
                $keuangan = Keuangan::find($payment['keuangan_id']);
                if ($keuangan) {
                    $keuangan->perincian_bayar = $payment['perincian_bayar'];
                    $keuangan->jenis = $payment['jenis_pembayaran'];
                    $keuangan->save();
                }
            } elseif ($payment['jenis'] === 'manual') {
                $keuangan = Keuangan::find($payment['id']);
                if ($keuangan) {
                    $keuangan->perincian_bayar = $payment['perincian_bayar'];
                    $keuangan->jenis = $payment['jenis_pembayaran'];
                    $keuangan->save();
                }
            }
        }

        $keuangans = Keuangan::where('usulan_id', $this->usulan_id)
            ->where('pegawai_id', $this->pegawai_id)
            ->get();
        foreach ($keuangans as $keuangan) {
            $keuangan->maksud_perjalanan_dinas = $this->maksud_perjalanan_dinas;
            $keuangan->alat_angkut = $this->alat_angkut;
            $keuangan->tempat_berangkat = $this->tempat_berangkat;
            $keuangan->tempat_tujuan = $this->tempat_tujuan;
            $keuangan->save();
        }

        session()->flash('success', 'Semua pembayaran berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.keuangans.keuangan-preview')
            ->layout('layouts.app');
    }
}
