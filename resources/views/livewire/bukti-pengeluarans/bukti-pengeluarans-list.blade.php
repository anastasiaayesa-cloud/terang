<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Bukti Pengeluaran') }}
    </h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold">Daftar Bukti Pengeluaran</h3>
                        <p class="text-sm text-gray-600">Kelola semua bukti pengeluaran berdasarkan kegiatan</p>
                    </div>
                    <a href="{{ route('bukti-pengeluarans.upload') }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 inline-flex items-center space-x-2 transition">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <span>Upload Bukti Baru</span>
                    </a>
                </div>

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-50 text-green-700 rounded border border-green-200">
                        {{ session('success') }}
                    </div>
                @endif

                @if (count($groupedData) > 0)
                    
                    @foreach ($groupedData as $group)
                    <div class="mb-6 border rounded-lg overflow-hidden shadow-sm">
                        <div class="bg-gray-50 px-4 py-4 flex justify-between items-center border-b">
                            <div class="flex items-start space-x-3">
                                <div class="bg-blue-100 p-2 rounded-lg">
                                    <span class="text-xl">📌</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-lg leading-tight">
                                        {{ $group['perencanaan']->usulan->nama_kegiatan ?? 'Kegiatan Tidak Teridentifikasi' }}
                                    </h4>
                                    <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider">
                                        Komponen: <span class="font-semibold">{{ $group['perencanaan']->nama_komponen }}</span> 
                                        @if($group['perencanaan']->kode)
                                            • Kode: {{ $group['perencanaan']->kode }}
                                        @endif
                                        • {{ $group['fileCount'] }} Lampiran
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 font-medium uppercase">Total Realisasi:</p>
                                <p class="text-xl font-bold text-green-700">
                                    Rp {{ number_format($group['totalNominal'], 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        
                        @if($group['fileCount'] > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr class="bg-white">
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">#</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tipe Bukti</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">File</th>
                                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase">Nominal</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Keterangan</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach ($group['buktiList'] as $index => $bukti)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $bukti->tipe_bukti_label_icon }} {{ $bukti->tipe_bukti_label }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm text-gray-700">{{ Str::limit($bukti->file_name, 20) }}</span>
                                                <a href="{{ asset('storage/' . $bukti->file_path) }}" target="_blank" class="text-gray-400 hover:text-blue-600" title="Lihat">
                                                    👁️
                                                </a>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right font-semibold text-sm text-gray-900">
                                            Rp {{ number_format($bukti->nominal, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            {{ $bukti->tanggal_bukti ? $bukti->tanggal_bukti->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500 italic">
                                            {{ Str::limit($bukti->keterangan, 30) ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex items-center justify-center space-x-3">
                                                <a href="{{ route('bukti-pengeluarans.edit', $bukti->id) }}" class="text-blue-600 hover:text-blue-800">
                                                    ✏️
                                                </a>
                                                <button wire:click="deleteBukti({{ $bukti->id }})" wire:confirm="Hapus bukti ini?" class="text-red-600 hover:text-red-800">
                                                    🗑️
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="p-8 text-center text-gray-500 text-sm">
                            Belum ada bukti pengeluaran diunggah. 
                            <a href="{{ route('bukti-pengeluarans.upload') }}" class="text-blue-600 hover:underline font-medium">Upload sekarang</a>
                        </div>
                        @endif
                    </div>
                    @endforeach

                    <div class="mt-8 p-5 bg-green-600 rounded-lg shadow-md">
                        <div class="flex items-center justify-between text-white">
                            <div>
                                <p class="text-xs uppercase tracking-widest font-semibold opacity-80">Akumulasi Seluruh Pengeluaran</p>
                                <p class="text-2xl font-black">Total Akhir</p>
                            </div>
                            <div class="text-right">
                                <p class="text-3xl font-black">
                                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                @else
                    <div class="text-center py-20 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                        <div class="text-5xl mb-4">📂</div>
                        <h3 class="text-lg font-bold text-gray-900">Belum Ada Perencanaan</h3>
                        <p class="text-sm text-gray-500 mb-6">Silakan buat perencanaan terlebih dahulu untuk mengelola bukti pengeluaran.</p>
                        <a href="{{ route('perencanaans.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
                            + Buat Perencanaan Baru
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>