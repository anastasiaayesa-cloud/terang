<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Usulan Pembayaran 30% Biaya Penginapan') }}
    </h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">

                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold">Daftar Usulan Pembayaran</h3>
                        <p class="text-sm text-gray-600">Usulan pembayaran 30% biaya penginapan (lumpsum)</p>
                    </div>
                    <a href="{{ route('usulan-pembayarans.create') }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 inline-flex items-center space-x-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Usulan</span>
                    </a>
                </div>

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-50 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (count($usulans) > 0)

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-3 py-3 border text-left text-xs font-medium text-gray-500">#</th>
                                    <th class="px-3 py-3 border text-left text-xs font-medium text-gray-500">Perencanaan</th>
                                    <th class="px-3 py-3 border text-left text-xs font-medium text-gray-500">Pegawai</th>
                                    <th class="px-3 py-3 border text-left text-xs font-medium text-gray-500">Provinsi</th>
                                    <th class="px-3 py-3 border text-left text-xs font-medium text-gray-500">Tanggal</th>
                                    <th class="px-3 py-3 border text-right text-xs font-medium text-gray-500">Malam</th>
                                    <th class="px-3 py-3 border text-left text-xs font-medium text-gray-500">Golongan</th>
                                    <th class="px-3 py-3 border text-right text-xs font-medium text-gray-500">Tarif SBM</th>
                                    <th class="px-3 py-3 border text-right text-xs font-medium text-gray-500">Total</th>
                                    <th class="px-3 py-3 border text-center text-xs font-medium text-gray-500">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usulans as $index => $usulan)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-3 border text-sm">{{ $index + 1 }}</td>
                                    <td class="px-3 py-3 border text-sm">
                                        {{ Str::limit($usulan->perencanaan->nama_komponen, 25) ?? '-' }}
                                    </td>
                                    <td class="px-3 py-3 border">
                                        <div class="text-sm font-medium">{{ $usulan->pegawai->nama ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $usulan->pegawai->nip ?? '' }}</div>
                                    </td>
                                    <td class="px-3 py-3 border text-sm">{{ $usulan->provinsi_tujuan }}</td>
                                    <td class="px-3 py-3 border text-sm">
                                        {{ $usulan->tanggal_mulai->format('d/m/Y') }} - {{ $usulan->tanggal_selesai->format('d/m/Y') }}
                                    </td>
                                    <td class="px-3 py-3 border text-sm text-right">{{ $usulan->jumlah_malam }}</td>
                                    <td class="px-3 py-3 border text-sm">
                                        <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">
                                            {{ $usulan->golongan_label }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 border text-sm text-right">
                                        Rp {{ number_format($usulan->tarif_hotel_sbm, 0, ',', '.') }}
                                    </td>
                                    <td class="px-3 py-3 border text-sm text-right font-semibold text-green-700">
                                        Rp {{ number_format($usulan->total_nominal, 0, ',', '.') }}
                                    </td>
                                    <td class="px-3 py-3 border text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('usulan-pembayarans.edit', $usulan->id) }}"
                                               class="text-blue-600 hover:text-blue-800" title="Edit">
                                                ✏️
                                            </a>
                                            <button
                                                wire:click="delete({{ $usulan->id }})"
                                                wire:confirm="Apakah Anda yakin ingin menghapus usulan pembayaran ini?"
                                                class="text-red-600 hover:text-red-800" title="Hapus">
                                                🗑️
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-green-50">
                                    <td colspan="8" class="px-3 py-3 border text-right font-bold text-green-800">
                                        TOTAL SEMUA USULAN:
                                    </td>
                                    <td class="px-3 py-3 border text-right font-bold text-green-800">
                                        Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                    </td>
                                    <td class="px-3 py-3 border"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada usulan pembayaran</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulai buat usulan pembayaran 30% biaya penginapan.</p>
                        <div class="mt-6">
                            <a href="{{ route('usulan-pembayarans.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Tambah Usulan Pertama
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
