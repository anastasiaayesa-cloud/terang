<?php
function angkaKeTerbilang($angka)
{
    $angka = abs((int)$angka);
    if ($angka == 0) return 'nol';
    $bilangan = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
    $result = '';
    if ($angka < 12) {
        $result = $bilangan[$angka];
    } elseif ($angka < 20) {
        $result = angkaKeTerbilang($angka - 10) . ' belas';
    } elseif ($angka < 100) {
        $result = angkaKeTerbilang(floor($angka / 10)) . ' puluh ' . angkaKeTerbilang($angka % 10);
    } elseif ($angka < 200) {
        $result = 'seratus ' . angkaKeTerbilang($angka - 100);
    } elseif ($angka < 1000) {
        $result = angkaKeTerbilang(floor($angka / 100)) . ' ratus ' . angkaKeTerbilang($angka % 100);
    } elseif ($angka < 2000) {
        $result = 'seribu ' . angkaKeTerbilang($angka - 1000);
    } elseif ($angka < 1000000) {
        $result = angkaKeTerbilang(floor($angka / 1000)) . ' ribu ' . angkaKeTerbilang($angka % 1000);
    } elseif ($angka < 1000000000) {
        $result = angkaKeTerbilang(floor($angka / 1000000)) . ' juta ' . angkaKeTerbilang($angka % 1000000);
    }
    return trim($result);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Dokumen Perjalanan Dinas</title>
    <style>
        /* CSS Global & Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, Helvetica, sans-serif; 
            font-size: 10pt; 
            line-height: 1.4; 
            color: #000; 
            padding: 30px; 
        }
        
        /* Kop Surat */
        .kop-surat { 
            text-align: center; 
            border-bottom: 2px solid #000; 
            padding-bottom: 10px; 
            margin-bottom: 15px; 
        }
        
        /* Judul Section */
        .section-title { 
            text-align: center; 
            font-weight: bold; 
            font-size: 11pt; 
            text-decoration: underline; 
            margin-bottom: 10px; 
        }

        /* Tabel Utama */
        table { width: 100%; border-collapse: collapse; }
        .table-border th, .table-border td { 
            border: 1px solid #000; 
            padding: 6px 8px; 
            vertical-align: middle; 
        }
        
        /* Utilitas */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .currency-flex { display: flex; justify-content: space-between; width: 100%; }

        /* Tanda Tangan */
        .ttd-table td { border: none !important; vertical-align: top; padding: 5px 0; }

        /* Page Break */
        .page-break { page-break-before: always; }

        @media print {
            body { padding: 0; }
            .no-print { display: none; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body>

    <div class="kop-surat">
        <img src="{{ public_path('kop-surat.jpg') }}" alt="Kop Surat" style="max-width: 100%; height: auto;">
    </div>

    <div class="section-title">RINCIAN BIAYA PERJALANAN DINAS</div>

    <div class="text-center" style="margin-bottom: 15px;">
        <p><strong>{{ $usulan->nama_kegiatan ?? '-' }}</strong> {{ $usulan->lokasi_kegiatan ? ' di ' . $usulan->lokasi_kegiatan : '' }}</p>
        <p>Pada tanggal 
            @php
                \Carbon\Carbon::setLocale('id');
                $start = $usulan->tanggal_kegiatan ? \Carbon\Carbon::parse($usulan->tanggal_kegiatan) : null;
                $end = $usulan->sampai_tanggal ? \Carbon\Carbon::parse($usulan->sampai_tanggal) : null;
            @endphp
            @if($start && $end)
                {{ $start->isoFormat('D MMMM YYYY') }} s.d. {{ $end->isoFormat('D MMMM YYYY') }}
            @else
                {{ $start ? $start->isoFormat('D MMMM YYYY') : '-' }}
            @endif
        </p>
    </div>

    <table style="margin-bottom: 15px; width: auto;">
        <tr>
            <td style="padding-right: 10px;">Lampiran SPPD Nomor</td>
            <td style="padding-right: 5px;">:</td>
            <td>{{ $nomor_surat_surat_tugas ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>{{ $tanggal_upload_surat_tugas ? \Carbon\Carbon::parse($tanggal_upload_surat_tugas)->isoFormat('D MMMM YYYY') : '-' }}</td>
        </tr>
    </table>

    <table class="table-border">
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th style="width: 50%;">Perincian Biaya</th>
                <th style="width: 30%;">Jumlah</th>
                <th style="width: 15%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($payments as $payment)
                @php 
                    $qty = $payment['jumlah'] ?? 1;
                    $price = $payment['nominal'] ?? 0;
                    $subtotal = $price * $qty;
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>
                        {{ $payment['perincian_bayar'] ?? '-' }}
                        @if($qty > 1)
                            <div style="margin-top: 4px; display: flex; justify-content: space-between; font-size: 9pt;">
                                <div class="currency-flex" style="width: 45%;">
                                    <span>Rp.</span><span>{{ number_format($price, 0, ',', '.') }}</span>
                                </div>
                                <div style="width: 45%; text-align: right;">X {{ $qty }} {{ $payment['satuan'] ?? 'hr' }}</div>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="currency-flex">
                            <span>Rp.</span><span>{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                    </td>
                    <td>{{ $payment['keterangan'] ?? '' }}</td>
                </tr>
            @endforeach
            <tr class="font-bold">
                <td></td>
                <td class="text-center">Jumlah</td>
                <td>
                    <div class="currency-flex">
                        <span>Rp.</span><span>{{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4">
                    <strong>Terbilang : </strong>  
                    <span class="font-bold uppercase" style="font-style: italic;">
                        {{ angkaKeTerbilang($grandTotal) }} RUPIAH
                    </span>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="ttd-table" style="margin-top: 20px;">
        <tr>
            <td style="width: 50%;">
                Telah dibayar sejumlah<br>
                <strong>Rp. {{ number_format($grandTotal, 0, ',', '.') }}</strong><br>
                Bendahara Pengeluaran,
            </td>
            <td style="width: 50%; padding-left: 60px;">
                Bintan, {{ $tanggal_kwitansi ? \Carbon\Carbon::parse($tanggal_kwitansi)->isoFormat('D MMMM YYYY') : '-' }}<br>
                Telah menerima uang sebesar,<br>
                <strong>Rp. {{ number_format($grandTotal, 0, ',', '.') }}</strong><br>
                Yang menerima uang,
            </td>
        </tr>
        <tr>
            <td style="padding-top: 55px;">
                <u><strong>{{ $pejabat['bendahara_nama'] }}</strong></u><br>
                NIP. {{ $pejabat['bendahara_nip'] }}
            </td>
            <td style="padding-top: 55px; padding-left: 60px;">
                <u><strong>{{ $kepegawaian->nama ?? '-' }}</strong></u><br>
                NIP. {{ $kepegawaian->nip ?? '-' }}
            </td>
        </tr>
    </table>

    <div style="border-top: 1px dashed #000; margin: 15px 0;"></div>

    <table class="ttd-table">
        <tr><td colspan="2" class="text-center font-bold" style="padding-bottom: 10px;">PERHITUNGAN SPPD RAMPUNG</td></tr>
        <tr>
            <td style="width: 55%;">
                <table style="width: 100%;">
                    <tr><td width="45%">Ditetapkan sejumlah</td><td width="5%">:</td><td>Rp. {{ number_format($grandTotal, 0, ',', '.') }}</td></tr>
                    <tr><td>Yang telah dibayarkan semula</td><td>:</td><td>Rp. {{ number_format($grandTotal, 0, ',', '.') }}</td></tr>
                    <tr><td>Sisa kurang/lebih</td><td>:</td><td>NIHIL</td></tr>
                </table>
            </td>
            <td style="width: 45%; text-align: center;">
                Pejabat Pembuat Komitmen,<br><br><br><br><br>
                <u><strong>{{ $pejabat['ppk_nama'] }}</strong></u><br>
                NIP. {{ $pejabat['ppk_nip'] }}
            </td>
        </tr>
    </table>


    <div class="page-break"></div>
    
    <div class="kop-surat">
        <img src="{{ public_path('kop-surat.jpg') }}" alt="Kop Surat" style="max-width: 100%; height: auto;">
    </div>

    <div class="section-title">DAFTAR PENGELUARAN RIIL</div>

    <div style="margin-bottom: 15px;">
        <p>Yang bertanda tangan di bawah ini :</p>
        <table style="width: auto; margin-top: 5px; border: none;">
            <tr><td width="150">Nama</td><td width="10">:</td><td class="font-bold">{{ $kepegawaian->nama ?? '-' }}</td></tr>
            <tr><td>NIP</td><td>:</td><td>{{ $kepegawaian->nip ?? '-' }}</td></tr>
            <tr><td>Pangkat / Golongan</td><td>:</td><td>{{ $kepegawaian->pangkat->nama ?? '-' }}</td></tr>
            <tr><td>Jabatan</td><td>:</td><td>{{ $kepegawaian->jabatan ?? '-' }}</td></tr>
            <tr><td>Unit Kerja</td><td>:</td><td>BPMP Provinsi Kepulauan Riau</td></tr>
        </table>
    </div>

    <p style="margin-bottom: 10px; text-align: justify;">
        Berdasarkan Surat Perintah Perjalanan Dinas (SPPD) Tanggal : {{ $tanggal_upload_surat_tugas ? \Carbon\Carbon::parse($tanggal_upload_surat_tugas)->isoFormat('D MMMM YYYY') : '-' }}, Nomor: {{ $nomor_surat_surat_tugas ?? '-' }}, Dengan ini, kami menyatakan dengan sesungguhnya bahwa:
    </p>

    <p>1. Biaya transport pegawai perjalanan dinas di UPT di bawah ini yang tidak dapat diperoleh bukti - bukti pengeluaran, meliputi :</p>

    <table class="table-border" style="margin-top: 10px;">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="text-align: center;">U R A I A N &nbsp; K E G I A T A N</th>
                <th style="width: 35%; text-align: center;">J U M L A H</th>
            </tr>
        </thead>
        <tbody>
            @php $no_riil = 1; @endphp
            @foreach ($payments as $payment)
            <tr>
                <td class="text-center">{{ $no_riil++ }}</td>
                <td>{{ $payment['perincian_bayar'] }}</td>
                <td>
                    <div class="currency-flex">
                        <span>Rp.</span><span>{{ number_format($payment['nominal'] * ($payment['jumlah'] ?? 1), 0, ',', '.') }}</span>
                    </div>
                </td>
            </tr>
            @endforeach
            <tr class="font-bold">
                <td colspan="2" class="text-center">J U M L A H</td>
                <td>
                    <div class="currency-flex">
                        <span>Rp.</span><span>{{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <div style="margin: 15px 0; text-align: justify;">
        <p>2. Jumlah uang tersebut di atas benar - benar dikeluarkan untuk pelaksanaan perjalanan dinas dimaksud dan apabila di kemudian hari terdapat kelebihan atas pembayaran, kami bersedia untuk menyetorkan kelebihan tersebut ke Kas Negara.</p>
    </div>

    <p>Demikian pernyataan ini kami buat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.</p>

    <table class="ttd-table" style="margin-top: 30px;">
        <tr>
            <td style="width: 50%;">
                Mengetahui / Menyetujui<br>
                Pejabat Pembuat Komitmen,<br><br><br><br><br>
                <u><strong>{{ $pejabat['ppk_nama'] }}</strong></u><br>
                NIP. {{ $pejabat['ppk_nip'] }}
            </td>
            <td style="width: 50%; padding-left: 60px;">
                Bintan, {{ $tanggal_kwitansi ? \Carbon\Carbon::parse($tanggal_kwitansi)->isoFormat('D MMMM YYYY') : '-' }}<br>
                Pelaksana SPD,<br><br><br><br><br>
                <u><strong>{{ $kepegawaian->nama ?? '-' }}</strong></u><br>
                NIP. {{ $kepegawaian->nip ?? '-' }}
            </td>
        </tr>
    </table>


    <div class="page-break"></div>
    
    <div class="kop-surat">
        <img src="{{ public_path('kop-surat.jpg') }}" alt="Kop Surat" style="max-width: 100%; height: auto;">
    </div>

    <div class="section-title" style="margin-bottom: 25px;">SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK</div>

    <div style="margin-bottom: 20px;">
        <p>Yang bertanda tangan di bawah ini :</p>
        <table style="width: auto; margin-top: 10px; border: none;">
            <tr>
                <td width="120">Nama</td>
                <td width="15">:</td>
                <td class="font-bold">{{ $kepegawaian->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $kepegawaian->jabatan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Instansi</td>
                <td>:</td>
                <td>BPMP Provinsi Kepulauan Riau</td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 20px;">
        <p>Menerangkan dengan sebenarnya :</p>
        <table style="width: 100%; border: none; margin-top: 10px; line-height: 1.6;">
            <tr style="vertical-align: top;">
                <td width="30">1.</td>
                <td style="text-align: justify;">
                    Bahwa pada tanggal 
                    @if($start && $end)
                        {{ $start->isoFormat('D MMMM YYYY') }} s.d. {{ $end->isoFormat('D MMMM YYYY') }}
                    @else
                        {{ $start ? $start->isoFormat('D MMMM YYYY') : '-' }}
                    @endif
                    sedang tidak mengikuti Kegiatan dari Unit Kerja lainnya.
                </td>
            </tr>
            <tr style="vertical-align: top;">
                <td>2.</td>
                <td style="text-align: justify;">
                    Apabila bukti Perjalanan Dinas (Keaslian Tiket, Boarding Pass, Airport tax, Bill Hotel, dll) nama dan tanggal tidak tercatat dalam manifest penerbangan, serta tidak sesuai dengan harga yang sebenarnya, bersedia mengembalikan biaya perjalanan dinas yang tidak sesuai pada saat di audit oleh Instansi terkait.
                </td>
            </tr>
            <tr style="vertical-align: top;">
                <td>3.</td>
                <td style="text-align: justify;">
                    Bertanggung jawab dalam menyelesaikan laporan perjalanan dinas sesuai ketentuan.
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 30px;">
        <p>Demikian Surat pernyataan ini dibuat dengan sebenar-benarnya.</p>
    </div>

    <table class="ttd-table" style="margin-top: 50px;">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%; padding-left: 60px;">
                Yang membuat pernyataan<br><br><br><br><br><br>
                <strong>{{ $kepegawaian->nama ?? '-' }}</strong><br>
                NIP. {{ $kepegawaian->nip ?? '-' }}
            </td>
        </tr>
    </table>

</body>
</html>