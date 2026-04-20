@php
    use Illuminate\Support\Facades\Storage;
    use Carbon\Carbon;
@endphp

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Manajemen Persuratan') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        {{-- Alert Notifikasi --}}
        @if (session('success'))
            <div class="p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm" role="alert">
                <p class="font-bold">Berhasil</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm" role="alert">
                <p class="font-bold">Kesalahan</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="p-6 text-gray-900">

                {{-- Bagian Pencarian --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                    <div class="relative group">
                        <input type="text" 
                               wire:model.live.debounce.300ms="search"
                               placeholder="Cari komponen atau kegiatan..."
                               class="border-gray-300 rounded-xl px-4 py-2.5 w-full md:w-96 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-all pl-11">
                        <span class="absolute left-4 top-3 text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                    </div>
                </div>

                {{-- TABEL 1: Perencanaan Siap Buat Surat --}}
                <section class="mb-12">
                    <div class="flex items-center mb-5">
                        <div class="p-2 bg-emerald-100 rounded-lg mr-3">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg text-gray-800">Perencanaan Menunggu Surat</h3>
                    </div>

                    @if($perencanaansSiapSurat->isNotEmpty())
                        <div class="overflow-x-auto border border-gray-200 rounded-xl shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Detail Perencanaan</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pegawai Terverifikasi</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($perencanaansSiapSurat as $perencanaan)
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-6 py-4">
                                                <div class="text-xs font-mono font-bold text-indigo-600 uppercase tracking-wider mb-1">{{ $perencanaan->kode ?? 'TANPA KODE' }}</div>
                                                <div class="text-sm font-semibold text-gray-900 mb-1">{{ $perencanaan->nama_komponen }}</div>
                                                <div class="text-xs text-gray-500 flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path></svg>
                                                    {{ $perencanaan->usulan->nama_kegiatan ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($perencanaan->usulan->usulanPegawais->where('status', 'approved') as $up)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                            {{ $up->kepegawaian->nama ?? 'Unknown' }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <a href="{{ route('persuratans.create', ['perencanaan_id' => $perencanaan->id]) }}"
                                                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-widest rounded-lg transition-all shadow-sm hover:shadow-md">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
                                                    Buat Surat
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-10 text-center border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/50">
                            <div class="text-gray-400 mb-2">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada perencanaan yang siap dibuatkan surat.</p>
                        </div>
                    @endif
                </section>

                <hr class="my-10 border-gray-100">

                {{-- TABEL 2: Riwayat Persuratan --}}
                <section>
                    <div class="flex items-center mb-5">
                        <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg text-gray-800">Riwayat Persuratan Selesai</h3>
                    </div>

                    <div class="overflow-x-auto border border-gray-200 rounded-xl shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-4 text-center text-xs font-bold text-gray-500 uppercase">No</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Komponen & Kegiatan</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pegawai</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Tgl Upload</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Berkas & Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @php
                                    $groupedPersurats = $persurats->getCollection()->groupBy('perencanaan_id');
                                @endphp

                                @forelse($groupedPersurats as $perencanaanId => $group)
                                    @php $firstItem = $group->first(); @endphp
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-4 py-4 text-center text-sm font-medium text-gray-400">
                                            {{ $loop->iteration + ($persurats->currentPage() - 1) * $persurats->perPage() }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-gray-900 uppercase">{{ $firstItem->perencanaan->kode ?? '-' }}</div>
                                            <div class="text-sm text-gray-600 mb-2">{{ $firstItem->perencanaan->nama_komponen ?? '-' }}</div>
                                            <div class="text-[10px] inline-flex items-center px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-bold uppercase">
                                                {{ $firstItem->perencanaan->usulan->nama_kegiatan ?? 'Mandiri' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col gap-1">
                                                @php
                                                    // Ambil semua pegawai dari seluruh item dalam grup ini, lalu buat unik berdasarkan ID
                                                    $uniquePegawais = $group->flatMap(function($item) {
                                                        return $item->pegawais;
                                                    })->unique('id');
                                                @endphp

                                                @if($uniquePegawais->isNotEmpty())
                                                    @foreach($uniquePegawais as $p)
                                                        <div class="text-sm text-gray-700 flex items-center">
                                                            <span class="mr-1.5 text-indigo-500">•</span>
                                                            {{ $p->nama }}
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="text-sm text-gray-400">• N/A</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm text-gray-600">
                                            {{ Carbon::parse($firstItem->tanggal_upload)->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap justify-center items-center gap-2">
                                                {{-- Berkas PDF --}}
                                                @foreach($group as $item)
                                                    @if($item->file_pdf)
                                                        <a href="{{ Storage::url($item->file_pdf) }}" 
                                                        target="_blank"
                                                        class="p-2 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg hover:bg-emerald-600 hover:text-white transition-all shadow-sm"
                                                        title="Unduh PDF. Pegawai: {{ $item->pegawais->pluck('nama')->implode(', ') }}">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                        </a>
                                                    @endif
                                                @endforeach

                                                {{-- Tombol Edit & Hapus (Mengambil ID dari item pertama dalam grup) --}}
                                                @if($firstItem && isset($firstItem->id))
                                                    <div class="flex items-center gap-2 ml-2 pl-2 border-l border-gray-200">
                                                        <a href="{{ route('persuratans.edit', $firstItem->id) }}"
                                                           class="p-2 bg-amber-50 text-amber-600 border border-amber-100 rounded-lg hover:bg-amber-500 hover:text-white transition-all shadow-sm"
                                                           title="Edit Data">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2a2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                        </a>
                                                        
                                                        <button type="button" 
                                                                wire:click="delete({{ $firstItem->id }})"
                                                                wire:confirm="Hapus data persuratan ini?"
                                                                class="p-2 bg-rose-50 text-rose-600 border border-rose-100 rounded-lg hover:bg-rose-600 hover:text-white transition-all shadow-sm"
                                                                title="Hapus">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <div class="p-3 bg-gray-50 rounded-full mb-3">
                                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                </div>
                                                <p class="font-medium">Tidak ada riwayat ditemukan</p>
                                                <p class="text-xs text-gray-400">Coba ubah kata kunci pencarian Anda</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8">
                        {{ $persurats->links() }}
                    </div>
                </section>

            </div>
        </div>
    </div>
</div>