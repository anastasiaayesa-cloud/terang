<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\BuktiPengeluaran;
use App\Models\Keuangan;
use App\Models\Persuratan;
use App\Models\Usulan;
use App\Models\UsulanPegawai;
use Barryvdh\DomPDF\Facade\Pdf;

class KeuanganPreviewController extends Controller
{
    public function cetak($usulan_id, $pegawai_id)
    {
        $usulan = Usulan::find($usulan_id);
        $pegawai = UsulanPegawai::where('usulan_id', $usulan_id)
            ->where('pegawai_id', $pegawai_id)
            ->with('kepegawaian.pangkat')
            ->first();

        if (! $pegawai) {
            abort(404, 'Data tidak ditemukan');
        }

        $kepegawaian = $pegawai->kepegawaian;

        $suratTugas = Persuratan::where('usulan_id', $usulan_id)
            ->where('persuratan_kategori_id', 2)
            ->first();
        $nomor_surat_surat_tugas = $suratTugas?->nomor_surat;
        $tanggal_upload_surat_tugas = $suratTugas?->tanggal_upload;

        $buktiPengeluarans = BuktiPengeluaran::where('usulan_id', $usulan_id)
            ->where('pegawai_id', $pegawai_id)
            ->with('keuangan')
            ->get();

        $manualPayments = Keuangan::where('usulan_id', $usulan_id)
            ->where('pegawai_id', $pegawai_id)
            ->whereNull('bukti_pengeluaran_id')
            ->get();

        $payments = collect();

        foreach ($buktiPengeluarans as $bukti) {
            $payments->push([
                'perincian_bayar' => $bukti->keuangan?->perincian_bayar ?? $bukti->keterangan,
                'jenis' => $bukti->keuangan?->jenis ?? 'Biaya Perjalanan Dinas',
                'nominal' => $bukti->nominal,
                'jumlah' => 1,
                'satuan' => $bukti->keuangan?->satuan ?? null,
                'uang_dibayarkan' => $bukti->keuangan?->uang_dibayarkan,
                'tipe' => 'bukti',
                'bukti_pengeluaran_id' => $bukti->id,
            ]);
        }

        foreach ($manualPayments as $manual) {
            $payments->push([
                'perincian_bayar' => $manual->perincian_bayar,
                'jenis' => $manual->jenis,
                'nominal' => $manual->nominal,
                'jumlah' => $manual->jumlah,
                'satuan' => $manual->satuan,
                'uang_dibayarkan' => $manual->uang_dibayarkan,
                'tipe' => 'manual',
                'bukti_pengeluaran_id' => $manual->bukti_pengeluaran_id,
            ]);
        }

        $payments = $payments->values()->all();

        $grandTotal = collect($payments)->sum('uang_dibayarkan') ?? 0;

        $pejabat = [
            'bendahara_nama' => 'Hilman Aquarito, S.Si',
            'bendahara_nip' => '198802192015041001',
            'ppk_nama' => 'Erwin Yan Irawan, S.T.',
            'ppk_nip' => '198507292015041001',
        ];

        $nomor_surat = null;

        $firstKeuangan = Keuangan::where('usulan_id', $usulan_id)
            ->where('pegawai_id', $pegawai_id)
            ->first();
        $tanggal_kwitansi = $firstKeuangan?->tanggal_kwitansi;
        $maksud_perjalanan_dinas = $firstKeuangan?->maksud_perjalanan_dinas;
        $alat_angkut = $firstKeuangan?->alat_angkut;
        $tempat_berangkat = $firstKeuangan?->tempat_berangkat;
        $tempat_tujuan = $firstKeuangan?->tempat_tujuan;

        $data = compact(
            'usulan',
            'kepegawaian',
            'payments',
            'grandTotal',
            'pejabat',
            'nomor_surat',
            'tanggal_kwitansi',
            'nomor_surat_surat_tugas',
            'tanggal_upload_surat_tugas',
            'maksud_perjalanan_dinas',
            'alat_angkut',
            'tempat_berangkat',
            'tempat_tujuan'
        );

        $pdf = Pdf::loadView('livewire.keuangans.kwitansi-preview', $data);

        return $pdf->stream('kwitansi-'.date('Ymd').'.pdf');
    }
}
