<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        {{-- Header Ringkasan --}}
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Dashboard Anggaran</h2>
            <p class="text-sm text-gray-500 mt-1">Pantau rincian kegiatan, kuota peserta, dan status pembayaran berkala.</p>
        </div>

        {{-- Section 1: Ringkasan Utama (Cards) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            {{-- Card 1: Total Anggaran Keseluruhan --}}
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl p-6 text-white shadow-md">
                <p class="text-xs font-semibold uppercase tracking-wider opacity-75">Total Anggaran Keseluruhan</p>
                <p class="text-2xl font-extrabold mt-2">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</p>
                <div class="mt-4 text-xs opacity-60 flex items-center gap-1">
                    <span>⚡ Terakumulasi dari semua tahun aktif</span>
                </div>
            </div>

            {{-- Card 2: Total Kegiatan Terdata --}}
            <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Kegiatan</p>
                <p class="text-2xl font-bold text-gray-800 mt-2">
                    {{ collect($anggarans)->sum('jumlah_kegiatan') }} <span class="text-sm font-medium text-gray-500">Kegiatan</span>
                </p>
                <div class="mt-4 text-xs text-blue-500 font-medium">Agenda resmi yang terdaftar</div>
            </div>

            {{-- Card 3: Petugas Sudah Lunas --}}
            <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Sudah Dibayarkan</p>
                <p class="text-2xl font-bold text-emerald-600 mt-2">
                    {{ collect($anggarans)->sum('jumlah_petugas_dibayarkan') }} <span class="text-sm font-medium text-gray-500">Orang</span>
                </p>
                <div class="mt-4 flex items-center gap-1 text-xs text-emerald-600">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span> Pembayaran selesai penuh
                </div>
            </div>

            {{-- Card 4: Petugas Pending/Sebagian --}}
            <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Belum Dibayarkan</p>
                <p class="text-2xl font-bold text-rose-600 mt-2">
                    {{ collect($anggarans)->sum('jumlah_petugas_belum_dibayarkan') }} <span class="text-sm font-medium text-gray-500">Orang</span>
                </p>
                <div class="mt-4 flex items-center gap-1 text-xs text-rose-500">
                    <span class="inline-block w-2 h-2 rounded-full bg-rose-500"></span> Menunggu pelunasan
                </div>
            </div>

        </div>

        {{-- Section 2: Tabel Breakdown Tahunan --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800">Rincian Anggaran Tahunan</h3>
                <p class="text-xs text-gray-400">Pembagian data aktivitas keuangan yang dikelompokkan per tahun kegiatan.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tahun</th>
                            <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Jumlah Kegiatan</th>
                            <th scope="col" class="px-6 py-3.5 class text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Jumlah Peserta</th>
                            <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Sudah Dibayarkan</th>
                            <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Belum Dibayarkan</th>
                            <th scope="col" class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Total Anggaran</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($anggarans as $item)
                            <tr wire:key="row-{{ $item['tahun'] }}" class="hover:bg-gray-50/70 transition-colors">
                                {{-- Tahun --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    {{ $item['tahun'] }}
                                </td>
                                {{-- Jumlah Kegiatan --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-600">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700">
                                        {{ $item['jumlah_kegiatan'] ?? 0 }} kegiatan
                                    </span>
                                </td>
                                {{-- Jumlah Peserta --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-600">
                                    {{ $item['jumlah_petugas'] ?? 0 }} orang
                                </td>
                                {{-- Sudah Dibayarkan --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                        {{ $item['jumlah_petugas_dibayarkan'] ?? 0 }} orang
                                    </span>
                                </td>
                                {{-- Belum Dibayarkan --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700">
                                        {{ $item['jumlah_petugas_belum_dibayarkan'] ?? 0 }} orang
                                    </span>
                                </td>
                                {{-- Total Anggaran --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-gray-900">
                                    Rp {{ number_format($item['total'] ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <span class="text-2xl">📭</span>
                                        <p class="font-medium">Tidak ada data anggaran yang tersedia.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>