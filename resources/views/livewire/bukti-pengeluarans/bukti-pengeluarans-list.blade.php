<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Bukti Pengeluaran') }}
    </h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold">Daftar Bukti Pengeluaran</h3>
                        <p class="text-sm text-gray-600">Kelola semua bukti pengeluaran per perencanaan</p>
                    </div>
                    <a href="{{ route('bukti-pengeluarans.upload') }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 inline-flex items-center space-x-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <span>Upload Bukti Baru</span>
                    </a>
                </div>

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-50 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (count($groupedData) > 0)
                    
                    <!-- Groups -->
                    @foreach ($groupedData as $group)
                    <div class="mb-6 border rounded-lg overflow-hidden">
                        <!-- Group Header -->
                        <div class="bg-gray-50 px-4 py-3 flex justify-between items-center border-b">
                            <div>
                                <h4 class="font-semibold text-gray-800">
                                    📁 {{ $group['perencanaan']->nama_komponen }}
                                </h4>
                                <p class="text-xs text-gray-500">
                                    {{ $group['fileCount'] }} file{{ $group['fileCount'] !== 1 ? 's' : '' }}
                                    @if($group['perencanaan']->kode)
                                        • Kode: {{ $group['perencanaan']->kode }}
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">Total:</p>
                                <p class="font-bold text-green-700">
                                    Rp {{ number_format($group['totalNominal'], 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        
                        @if($group['fileCount'] > 0)
                        <!-- Table for this group -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-gray-50 border-b">
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">#</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Tipe Bukti</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">File</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Nominal</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Tanggal</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Keterangan</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($group['buktiList'] as $index => $bukti)
                                    <tr class="hover:bg-gray-50 border-b">
                                        <td class="px-4 py-3 text-sm">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center space-x-1 text-sm">
                                                <span>{{ $bukti->tipe_bukti_label_icon }}</span>
                                                <span>{{ $bukti->tipe_bukti_label }}</span>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm">{{ Str::limit($bukti->file_name, 20) }}</span>
                                                <a href="{{ asset('storage/' . $bukti->file_path) }}" 
                                                   target="_blank"
                                                   class="text-blue-600 hover:text-blue-800"
                                                   title="Preview">
                                                    👁️
                                                </a>
                                                <a href="{{ asset('storage/' . $bukti->file_path) }}" 
                                                   download
                                                   class="text-green-600 hover:text-green-800"
                                                   title="Download">
                                                    ⬇️
                                                </a>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right font-medium text-sm">
                                            Rp {{ number_format($bukti->nominal, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            {{ $bukti->tanggal_bukti ? $bukti->tanggal_bukti->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            {{ Str::limit($bukti->keterangan, 25) ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('bukti-pengeluarans.edit', $bukti->id) }}"
                                                   class="text-blue-600 hover:text-blue-800"
                                                   title="Edit">
                                                    ✏️
                                                </a>
                                                <button 
                                                    wire:click="deleteBukti({{ $bukti->id }})"
                                                    wire:confirm="Apakah Anda yakin ingin menghapus bukti ini?"
                                                    class="text-red-600 hover:text-red-800"
                                                    title="Hapus"
                                                >
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
                        <!-- Empty State for this group -->
                        <div class="p-4 text-center text-gray-500 text-sm">
                            Belum ada bukti pengeluaran. 
                            <a href="{{ route('bukti-pengeluarans.upload') }}" class="text-blue-600 hover:underline">
                                Upload sekarang
                            </a>
                        </div>
                        @endif
                    </div>
                    @endforeach

                    <!-- Grand Total -->
                    <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-green-800">💰 TOTAL SEMUA BUKTI:</span>
                            <span class="text-xl font-bold text-green-800">
                                Rp {{ number_format($grandTotal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada perencanaan</h3>
                        <p class="mt-1 text-sm text-gray-500">Buat perencanaan terlebih dahulu untuk mulai upload bukti pengeluaran.</p>
                        <div class="mt-6">
                            <a href="{{ route('perencanaans.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Buat Perencanaan
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
