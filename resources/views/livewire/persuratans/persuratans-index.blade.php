@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Manajemen Persuratan') }}
    </h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">

                {{-- Bagian Pencarian --}}
                <div class="flex items-center justify-between mb-6">
                    <div class="relative">
                        <input type="text" wire:model.live="search"
                            placeholder="Cari komponen, kegiatan, atau pegawai..."
                            class="border border-gray-300 rounded-lg px-4 py-2 w-80 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                        <span class="absolute right-3 top-2.5 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                    </div>
                </div>

                {{-- Alert Notifikasi --}}
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- TABEL 1: Perencanaan Siap Buat Surat --}}
                <div class="mb-10">
                    <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center">
                        <span class="p-2 bg-green-100 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </span>
                        Perencanaan Dengan Pegawai Approved
                    </h3>

                    @if($perencanaansSiapSurat->isNotEmpty())
                        <div class="overflow-x-auto border border-gray-200 rounded-xl shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Info Perencanaan</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pegawai Terlibat</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($perencanaansSiapSurat as $perencanaan)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-blue-600">{{ $perencanaan->kode ?? '-' }}</div>
                                            <div class="text-sm text-gray-900 font-medium">{{ $perencanaan->nama_komponen }}</div>
                                            <div class="text-xs text-gray-500 mt-1 italic">
                                                Kegiatan: {{ $perencanaan->usulan->nama_kegiatan ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($perencanaan->usulan->usulanPegawais as $up)
                                                    @if($up->status == 'approved')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                            {{ $up->kepegawaian->nama ?? 'Unknown' }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('persuratans.create', ['perencanaan_id' => $perencanaan->id]) }}"
                                               class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                Buat Surat
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-6 text-center border-2 border-dashed border-gray-200 rounded-xl bg-gray-50">
                            <p class="text-gray-500">Belum ada perencanaan dengan status pegawai approved.</p>
                        </div>
                    @endif
                </div>

                <hr class="my-8 border-gray-200">

                {{-- TABEL 2: Riwayat Persuratan --}}
                <div>
                    <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center">
                        <span class="p-2 bg-blue-100 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                            </svg>
                        </span>
                        Riwayat Persuratan
                    </h3>

                    <div class="overflow-x-auto border border-gray-200 rounded-xl shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Komponen & Kegiatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Pegawai</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @php
                                    $groupedPersurats = $persurats->getCollection()->groupBy('perencanaan_id');
                                @endphp

                                @forelse($groupedPersurats as $perencanaanId => $group)
                                    @php $firstItem = $group->first(); @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-center text-sm text-gray-500">
                                            {{ $loop->iteration + ($persurats->currentPage() - 1) * $persurats->perPage() }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-gray-800">{{ $firstItem->perencanaan->kode ?? '-' }}</div>
                                            <div class="text-sm text-gray-600">{{ $firstItem->perencanaan->nama_komponen ?? '-' }}</div>
                                            <div class="text-xs italic text-blue-500 mt-2 border-t pt-1">
                                                <span class="font-semibold">Kegiatan:</span> 
                                                {{ $firstItem->perencanaan->usulan->nama_kegiatan ?? 'Tanpa Usulan (Mandiri)' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="space-y-1">
                                                @foreach($group as $item)
                                                    <div class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded-md inline-block mr-1">
                                                        {{ $item->pegawai->nama ?? 'N/A' }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm text-gray-600 font-medium">
                                            {{ \Carbon\Carbon::parse($firstItem->tanggal_upload)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center items-center space-x-2">
                                                {{-- Tombol Download File (Hijau) --}}
                                                @foreach($group as $item)
                                                    @if($item->file_pdf)
                                                        <a href="{{ Storage::url($item->file_pdf) }}" 
                                                           target="_blank"
                                                           class="p-1.5 bg-green-100 text-green-700 rounded-lg hover:bg-green-600 hover:text-white transition shadow-sm"
                                                           title="Lihat Surat ({{ $item->pegawai->nama ?? 'User' }})">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                        </a>
                                                    @endif
                                                @endforeach

                                                @if($firstItem && isset($firstItem->id))
                                                    {{-- Tombol Edit (Biru) --}}
                                                    <a href="{{ route('persuratans.edit', $firstItem->id) }}"
                                                       class="p-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-600 hover:text-white transition shadow-sm"
                                                       title="Edit">
                                                       <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                    </a>
                                                    
                                                    {{-- Tombol Hapus (Merah) --}}
                                                    <button type="button" 
                                                        wire:click="delete({{ $firstItem->id }})"
                                                        wire:confirm="Apakah Anda yakin ingin menghapus riwayat persuratan ini?"
                                                        class="p-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-600 hover:text-white transition shadow-sm"
                                                        title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                <p>Belum ada riwayat persuratan yang ditemukan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-6">
                        {{ $persurats->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>