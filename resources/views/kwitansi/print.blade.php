<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kwitansi - {{ $judulKwitansi }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; line-height: 1.4; color: #000; padding: 20px; }

        .kop-surat { text-align: center; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat img { max-height: 80px; margin-bottom: 5px; }
        .kop-surat h2 { font-size: 14pt; font-weight: bold; margin: 5px 0; }
        .kop-surat p { font-size: 10pt; }

        .section { margin-bottom: 30px; page-break-inside: avoid; }
        .section-title { text-align: center; font-weight: bold; font-size: 12pt; margin-bottom: 15px; text-decoration: underline; }

        .info-row { display: flex; margin-bottom: 5px; }
        .info-label { width: 200px; font-weight: normal; }
        .info-value { flex: 1; }

        table.rincian { width: 100%; border-collapse: collapse; margin: 15px 0; }
        table.rincian th, table.rincian td { border: 1px solid #000; padding: 6px 8px; text-align: left; vertical-align: top; }
        table.rincian th { background-color: #f0f0f0; font-weight: bold; text-align: center; }
        table.rincian td.text-right { text-align: right; }
        table.rincian td.text-center { text-align: center; }
        table.rincian tr.total-row { font-weight: bold; background-color: #f9f9f9; }

        .terbilang { font-style: italic; margin: 10px 0; }

        .tanda-tangan { display: flex; justify-content: space-between; margin-top: 30px; }
        .tanda-tangan .col { width: 45%; text-align: center; }
        .tanda-tangan .col p { margin-bottom: 5px; }
        .tanda-tangan .col .nama { font-weight: bold; margin-top: 60px; text-decoration: underline; }
        .tanda-tangan .col .nip { margin-top: 2px; }

        .perhitungan-box { border: 1px solid #000; padding: 15px; margin: 15px 0; }
        .perhitungan-box .row { display: flex; margin-bottom: 5px; }
        .perhitungan-box .row .label { width: 250px; }
        .perhitungan-box .row .value { flex: 1; }

        .pernyataan { text-align: justify; margin: 15px 0; line-height: 1.6; }
        .pernyataan ol { margin-left: 20px; }
        .pernyataan ol li { margin-bottom: 8px; }

        .page-break { page-break-before: always; }

        @media print {
            body { padding: 0; }
            .no-print { display: none; }
            .section { page-break-inside: avoid; }
        }

        .print-btn { position: fixed; top: 20px; right: 20px; z-index: 1000; }
        .print-btn button { background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 14px; }
        .print-btn button:hover { background: #1d4ed8; }
    </style>
</head>
<body>

    {{-- Print Button --}}
    <div class="print-btn no-print">
        <button onclick="window.print()">🖨️ Cetak Kwitansi</button>
    </div>

    {{-- ============================================ --}}
    {{-- SECTION 1: RINCIAN BIAYA PERJALANAN DINAS    --}}
    {{-- ============================================ --}}
    <div class="section">
        {{-- Kop Surat --}}
        <div class="kop-surat">
            {{-- GANTI src dengan path logo/kop surat kamu --}}
            {{-- <img src="{{ asset('images/kop-surat.png') }}" alt="Kop Surat"> --}}
            <div style="border: 2px solid #000; padding: 10px; margin-bottom: 10px;">
                <h2>KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</h2>
                <p>BALAI PENJAMINAN MUTU PENDIDIKAN PROVINSI KEPULAUAN RIAU</p>
                <p>Jl. [Alamat Lengkap] - Telp. [Nomor Telepon]</p>
            </div>
        </div>

        {{-- Judul --}}
        <div class="section-title">{{ $judulKwitansi }}</div>

        {{-- Info Kegiatan --}}
        <div class="info-row">
            <div class="info-label">Kegiatan</div>
            <div class="info-value">: {{ $pengajuan->perencanaan->nama_komponen ?? '-' }}</div>
        </div>
        @if ($pengajuan->nomor_surat)
        <div class="info-row">
            <div class="info-label">Lampiran SPPD Nomor</div>
            <div class="info-value">: {{ $pengajuan->nomor_surat }}</div>
        </div>
        @endif
        <div class="info-row">
            <div class="info-label">Tanggal</div>
            <div class="info-value">: {{ $pengajuan->tanggal_pengajuan->isoFormat('D MMMM YYYY') }}</div>
        </div>

        {{-- Tabel Rincian --}}
        <table class="rincian">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 60%;">Perincian Biaya</th>
                    <th style="width: 35%;">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp

                {{-- Transport --}}
                @foreach ($filterBukti->whereNotIn('tipe_bukti', ['tiket_hotel']) as $bukti)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $bukti->tipe_bukti_label }}{{ $bukti->keterangan ? ' - ' . $bukti->keterangan : '' }}</td>
                        <td class="text-right">Rp {{ number_format($bukti->nominal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach

                {{-- Uang Penginapan --}}
                @if ($showPenginapan && $totalPenginapan > 0)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>Uang Penginapan</td>
                        <td class="text-right">Rp {{ number_format($totalPenginapan, 0, ',', '.') }}</td>
                    </tr>
                @endif

                {{-- Uang Harian 1 --}}
                @if ($showUangHarian && $uangHarian1 > 0)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>Uang Harian ({{ $pengajuan->uang_harian_nominal }} x {{ $pengajuan->jumlah_hari }} hari)</td>
                        <td class="text-right">Rp {{ number_format($uangHarian1, 0, ',', '.') }}</td>
                    </tr>
                @endif

                {{-- Uang Harian 2 (jika ada) --}}
                @if ($showUangHarian && $uangHarian2 > 0)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>Uang Harian Tambahan</td>
                        <td class="text-right">Rp {{ number_format($uangHarian2, 0, ',', '.') }}</td>
                    </tr>
                @endif

                {{-- Total --}}
                <tr class="total-row">
                    <td colspan="2" class="text-center"><strong>Jumlah</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        {{-- Terbilang --}}
        <div class="terbilang">
            <strong>Terbilang:</strong> {{ ucwords($this->terbilang($grandTotal)) }} Rupiah
        </div>

        {{-- Pembayaran Info --}}
        <p style="margin: 10px 0;">Telah dibayar sejumlah: <strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></p>

        {{-- Tanda Tangan --}}
        <div class="tanda-tangan">
            <div class="col">
                <p>Bendahara Pengeluaran,</p>
                <p class="nama">{{ $pejabat['bendahara_nama'] }}</p>
                <p class="nip">NIP. {{ $pejabat['bendahara_nip'] }}</p>
            </div>
            <div class="col">
                <p>{{ $pengajuan->pegawai->tempat_lahir ?? 'Kota' }}, {{ $pengajuan->tanggal_pengajuan->copy()->addDays($pengajuan->jumlah_hari)->isoFormat('D MMMM YYYY') }}</p>
                <p>Telah menerima uang sebesar,</p>
                <p><strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></p>
                <p>Yang menerima uang,</p>
                <p class="nama">{{ $pengajuan->pegawai->nama ?? '-' }}</p>
                <p class="nip">NIP. {{ $pengajuan->pegawai->nip ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Perhitungan SPPD Rampung --}}
    <div class="section">
        <div class="perhitungan-box">
            <div style="font-weight: bold; margin-bottom: 10px;">PERHITUNGAN SPPD RAMPUNG</div>
            <div class="row">
                <div class="label">Ditetapkan sejumlah</div>
                <div class="value">: Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
            </div>
            <div class="row">
                <div class="label">Yang telah dibayarkan semula</div>
                <div class="value">: Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
            </div>
            <div class="row">
                <div class="label">Sisa kurang/lebih</div>
                <div class="value">: NIHIL</div>
            </div>
            <div style="margin-top: 20px; text-align: center;">
                <p>Pejabat Pembuat Komitmen,</p>
                <p class="nama" style="margin-top: 60px; font-weight: bold; text-decoration: underline;">{{ $pejabat['ppk_nama'] }}</p>
                <p>NIP. {{ $pejabat['ppk_nip'] }}</p>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- SECTION 2: DAFTAR PENGELUARAN RIIL           --}}
    {{-- ============================================ --}}
    <div class="section page-break">
        <div class="section-title">DAFTAR PENGELUARAN RIIL</div>

        <p style="margin-bottom: 10px;">Yang bertanda tangan di bawah ini:</p>

        <div class="info-row">
            <div class="info-label">Nama</div>
            <div class="info-value">: {{ $pengajuan->pegawai->nama ?? '-' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">NIP</div>
            <div class="info-value">: {{ $pengajuan->pegawai->nip ?? '-' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Pangkat / Golongan</div>
            <div class="info-value">: {{ $pengajuan->pegawai->pangkat->nama ?? '-' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Jabatan</div>
            <div class="info-value">: {{ $pengajuan->pegawai->jabatan ?? '-' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Unit Kerja</div>
            <div class="info-value">: {{ $pengajuan->pegawai->instansi->nama ?? '-' }}</div>
        </div>

        <p class="pernyataan">
            Berdasarkan Surat Perintah Perjalanan Dinas (SPPD) Tanggal: {{ $pengajuan->tanggal_pengajuan->isoFormat('D MMMM YYYY') }},
            Nomor: {{ $pengajuan->nomor_surat ?? '-' }}, Dengan ini, kami menyatakan dengan sesungguhnya bahwa:
        </p>

        <ol style="margin-left: 20px;">
            <li>
                Biaya transport pegawai perjalanan dinas di UPT di bawah ini yang tidak dapat diperoleh bukti – bukti pengeluaran, meliputi:
                <table class="rincian" style="margin-top: 10px;">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 65%;">Uraian Kegiatan</th>
                            <th style="width: 30%;">Jumlah (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; $totalRiil = 0; @endphp
                        @foreach ($filterBukti as $bukti)
                            @if (!$bukti->file_name)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>{{ $bukti->tipe_bukti_label }}{{ $bukti->keterangan ? ' - ' . $bukti->keterangan : '' }}</td>
                                    <td class="text-right">Rp {{ number_format($bukti->nominal, 0, ',', '.') }}</td>
                                </tr>
                                @php $totalRiil += $bukti->nominal; @endphp
                            @endif
                        @endforeach
                        <tr class="total-row">
                            <td colspan="2" class="text-center"><strong>JUMLAH</strong></td>
                            <td class="text-right"><strong>Rp {{ number_format($totalRiil, 0, ',', '.') }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </li>
            <li>
                Jumlah uang tersebut di atas benar – benar dikeluarkan untuk pelaksanaan perjalanan dinas dimaksud dan apabila di kemudian hari terdapat kelebihan atas pembayaran, kami bersedia untuk menyetorkan kelebihan tersebut ke Kas Negara.
            </li>
        </ol>

        <p class="pernyataan">
            Demikian pernyataan ini kami buat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.
        </p>

        {{-- Tanda Tangan --}}
        <div class="tanda-tangan" style="margin-top: 30px;">
            <div class="col">
                <p>Mengetahui / Menyetujui</p>
                <p>Pejabat Pembuat Komitmen,</p>
                <p class="nama">{{ $pejabat['ppk_nama'] }}</p>
                <p class="nip">NIP. {{ $pejabat['ppk_nip'] }}</p>
            </div>
            <div class="col">
                <p>{{ $pengajuan->pegawai->tempat_lahir ?? 'Kota' }}, {{ $pengajuan->tanggal_pengajuan->copy()->addDays($pengajuan->jumlah_hari)->isoFormat('D MMMM YYYY') }}</p>
                <p>Pelaksana SPD,</p>
                <p class="nama">{{ $pengajuan->pegawai->nama ?? '-' }}</p>
                <p class="nip">NIP. {{ $pengajuan->pegawai->nip ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- SECTION 3: SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK --}}
    {{-- ============================================ --}}
    <div class="section page-break">
        <div class="section-title">SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK</div>

        <p style="margin-bottom: 10px;">Yang bertanda tangan di bawah ini:</p>

        <div class="info-row">
            <div class="info-label">Nama</div>
            <div class="info-value">: {{ $pengajuan->pegawai->nama ?? '-' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Jabatan</div>
            <div class="info-value">: {{ $pengajuan->pegawai->jabatan ?? '-' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Instansi</div>
            <div class="info-value">: {{ $pengajuan->pegawai->instansi->nama ?? '-' }}</div>
        </div>

        <p class="pernyataan">Menerangkan dengan sebenarnya:</p>

        <ol style="margin-left: 20px;">
            <li>
                Bahwa pada tanggal {{ $pengajuan->tanggal_pengajuan->isoFormat('D MMMM YYYY') }} s.d. {{ $pengajuan->tanggal_pengajuan->copy()->addDays($pengajuan->jumlah_hari)->isoFormat('D MMMM YYYY') }} sedang tidak mengikuti Kegiatan dari Unit Kerja lainnya.
            </li>
            <li>
                Bertanggung jawab dalam menyelesaikan laporan perjalanan dinas sesuai ketentuan.
            </li>
            <li>
                Apabila bukti Perjalanan Dinas (Keaslian Tiket, Boarding Pass, Airport tax, Bill Hotel, dll) nama dan tanggal tidak tercatat dalam manifest penerbangan, serta tidak sesuai dengan harga yang sebenarnya, bersedia mengembalikan biaya perjalanan dinas yang tidak sesuai pada saat di audit oleh Instansi terkait.
            </li>
        </ol>

        <p class="pernyataan">
            Demikian Surat pernyataan ini dibuat dengan sebenar-benarnya.
        </p>

        <div style="text-align: center; margin-top: 30px;">
            <p>Yang membuat pernyataan</p>
            <p class="nama" style="margin-top: 60px; font-weight: bold; text-decoration: underline;">{{ $pengajuan->pegawai->nama ?? '-' }}</p>
            <p>NIP. {{ $pengajuan->pegawai->nip ?? '-' }}</p>
        </div>
    </div>

</body>
</html>