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
                        <p class="text-sm text-gray-600">Kelola bukti pengeluaran berdasarkan kegiatan dan pegawai terkait</p>
                    </div>
                    {{-- Tombol umum tetap ada, tapi akan diarahkan ke form kosong/default --}}
                    <a href="{{ route('bukti-pengeluarans.upload') }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 inline-flex items-center space-x-2 transition shadow-sm">
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
                    @php
                        $usulan = $group['perencanaan']->usulan;
                    @endphp
                    <div class="mb-8 border rounded-xl overflow-hidden shadow-sm bg-white">
                        {{-- Header Kegiatan --}}
                        <div class="bg-gray-50 px-5 py-4 border-b">
                            <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                                <div class="flex items-start space-x-4">
                                    <div class="bg-blue-600 p-2.5 rounded-lg shadow-md">
                                        <span class="text-xl text-white">📌</span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-xl leading-tight">
                                            {{ $usulan->nama_kegiatan ?? 'Kegiatan Tidak Teridentifikasi' }}
                                        </h4>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            <span class="text-xs font-medium px-2 py-1 bg-gray-200 text-gray-700 rounded">
                                                Komponen: {{ $group['perencanaan']->nama_komponen }}
                                            </span>
                                            <span class="text-xs font-medium px-2 py-1 bg-blue-100 text-blue-800 rounded">
                                                {{ $group['fileCount'] }} Lampiran Terdaftar
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 md:mt-0 text-left md:text-right">
                                    <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Total Realisasi</p>
                                    <p class="text-2xl font-black text-green-600">
                                        Rp {{ number_format($group['totalNominal'], 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Section Personal: Daftar Pegawai dalam Usulan --}}
                        <div class="p-5 bg-blue-50/30 border-b">
                            <p class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 15.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                Pilih Pegawai untuk Upload
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                {{-- ASUMSI: $usulan->usulanPegawais adalah relasi ke tabel usulan_pegawai/pegawai --}}
                                @if($usulan && $usulan->usulanPegawais)
                                    @foreach($usulan->usulanPegawais as $up)
                                        <a href="{{ route('bukti-pengeluarans.upload', ['usulan_id' => $usulan->id, 'pegawai_id' => $up->pegawai_id]) }}" ...>
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 ...">
                                                    {{-- Ubah ke kepegawaian --}}
                                                    {{ substr($up->kepegawaian->nama ?? '?', 0, 1) }}
                                                </div>
                                                <span class="text-sm ...">{{ $up->kepegawaian->nama ?? 'Nama Tidak Ada' }}</span>
                                            </div>
                                            <span class="text-xs ...">Upload →</span>
                                        </a>
                                    @endforeach
                                    @endif
                            </div>
                        </div>
                        
                        {{-- Tabel Detail File (Jika ada) --}}
                        @if($group['fileCount'] > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr class="bg-gray-50/50">
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">File & Tipe</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Oleh Pegawai</th>
                                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase">Nominal</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach ($group['buktiList'] as $bukti)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3">
                                            <div class="text-sm font-semibold text-gray-900">{{ Str::limit($bukti->file_name, 25) }}</div>
                                            <div class="text-xs text-blue-600">{{ $bukti->tipe_bukti_label }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm text-gray-700">{{ $bukti->pegawai->nama ?? '-' }}</div>
                                            <div class="text-xs text-gray-400">{{ $bukti->tanggal_bukti ? $bukti->tanggal_bukti->format('d M Y') : '' }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-right font-bold text-sm text-gray-900">
                                            Rp {{ number_format($bukti->nominal, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex items-center justify-center space-x-3">
                                                <a href="{{ asset('storage/' . $bukti->file_path) }}" target="_blank" class="p-1 hover:bg-gray-200 rounded" title="Lihat">👁️</a>
                                                <a href="{{ route('bukti-pengeluarans.edit', $bukti->id) }}" class="p-1 hover:bg-blue-100 rounded text-blue-600" title="Edit">✏️</a>
                                                <button wire:click="deleteBukti({{ $bukti->id }})" wire:confirm="Hapus bukti ini?" class="p-1 hover:bg-red-100 rounded text-red-600" title="Hapus">🗑️</button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                    @endforeach

                    {{-- Total Akhir --}}
                    <div class="mt-8 p-6 bg-gradient-to-r from-green-600 to-green-700 rounded-xl shadow-lg">
                        <div class="flex items-center justify-between text-white">
                            <div>
                                <p class="text-xs uppercase tracking-widest font-bold opacity-80">Akumulasi Seluruh Pengeluaran</p>
                                <p class="text-3xl font-black">Total Akhir</p>
                            </div>
                            <div class="text-right">
                                <p class="text-4xl font-black">
                                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                @else
                    {{-- Tampilan Kosong --}}
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