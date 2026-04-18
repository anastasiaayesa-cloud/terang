<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\PengajuanPencairan;

class KwitansiController extends Controller
{
    public function print($id, $jenis)
    {
        $validJenis = ['dinas-luar-kota', 'dinas-dalam-kota', 'dinas-dana-kantor', 'dinas-kegiatan'];

        if (! in_array($jenis, $validJenis)) {
            abort(404);
        }

        $pengajuan = PengajuanPencairan::with(['pegawai.pangkat', 'perencanaan', 'buktiPengeluarans'])
            ->findOrFail($id);

        $filterBukti = $this->filterBuktiByJenis($pengajuan->buktiPengeluarans, $jenis);

        $totalTransport = $filterBukti->whereNotIn('tipe_bukti', ['tiket_hotel'])->sum('nominal');
        $totalPenginapan = $filterBukti->whereIn('tipe_bukti', ['tiket_hotel'])->sum('nominal');
        $uangHarian1 = $pengajuan->uang_harian_total;
        $uangHarian2 = 0;

        $grandTotal = $totalTransport + $totalPenginapan + $uangHarian1 + $uangHarian2;

        $judulKwitansi = match ($jenis) {
            'dinas-luar-kota' => 'RINCIAN BIAYA PERJALANAN DINAS LUAR KOTA',
            'dinas-dalam-kota' => 'RINCIAN BIAYA PERJALANAN DINAS DALAM KOTA',
            'dinas-dana-kantor' => 'RINCIAN BIAYA PERJALANAN DINAS DANA KANTOR',
            'dinas-kegiatan' => 'RINCIAN BIAYA PERJALANAN DINAS KEGIATAN',
        };

        $showPenginapan = in_array($jenis, ['dinas-luar-kota', 'dinas-dana-kantor']);
        $showUangHarian = in_array($jenis, ['dinas-luar-kota', 'dinas-dalam-kota', 'dinas-kegiatan']);

        $pejabat = [
            'bendahara_nama' => 'Hilman Aquarito, S.Si',
            'bendahara_nip' => '198802192015041001',
            'ppk_nama' => 'Erwin Yan Irawan, S.T.',
            'ppk_nip' => '198507292015041001',
        ];

        return view('kwitansi.print', compact(
            'pengajuan',
            'jenis',
            'judulKwitansi',
            'filterBukti',
            'totalTransport',
            'totalPenginapan',
            'uangHarian1',
            'uangHarian2',
            'grandTotal',
            'showPenginapan',
            'showUangHarian',
            'pejabat'
        ));
    }

    private function filterBuktiByJenis($buktiList, $jenis)
    {
        return match ($jenis) {
            'dinas-luar-kota' => $buktiList,
            'dinas-dalam-kota' => $buktiList->whereIn('tipe_bukti', ['tiket_taxi', 'tiket_kereta']),
            'dinas-dana-kantor' => $buktiList,
            'dinas-kegiatan' => $buktiList->whereNotIn('tipe_bukti', ['tiket_hotel']),
        };
    }
}
