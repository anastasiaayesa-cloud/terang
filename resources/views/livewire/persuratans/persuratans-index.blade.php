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
            <div class="p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                <p class="font-bold">Berhasil</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="p-6 text-gray-900">

                {{-- Pencarian --}}
                <div class="mb-6">
                    <div class="relative group">
                        <input type="text" wire:model.live.debounce.300ms="search"
                               placeholder="Cari kegiatan atau nomor surat..."
                               class="border-gray-300 rounded-xl px-4 py-2.5 w-full md:w-96 focus:ring-2 focus:ring-indigo-500 shadow-sm pl-11">
                        <span class="absolute left-4 top-3 text-gray-400 group-focus-within:text-indigo-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto border border-gray-200 rounded-xl shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Informasi Kegiatan</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Status Surat</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            
                            {{-- Ganti $perencanaans ke $usulans --}}
                            @forelse($usulans as $usulan)
                                @php
                                    // Status surat diambil dari relasi persuratans pada model Usulan
                                    $dataSurat = $usulan->persuratans->first(); 
                                    $isSelesai = $usulan->persuratans->isNotEmpty();
                                @endphp

                                <tr x-data="{ open: false }" class="hover:bg-gray-50/50 transition border-l-4 {{ $isSelesai ? 'border-emerald-400' : 'border-amber-400' }}">
                                    <td class="px-6 py-4">
                                        <div class="text-[10px] font-mono font-bold text-indigo-600 uppercase">
                                            {{ $usulan->tanggal_kegiatan ? Carbon::parse($usulan->tanggal_kegiatan)->translatedFormat('d F Y') : 'N/A' }}
                                        </div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $usulan->nama_kegiatan }}</div>
                                        <div class="text-xs text-gray-500 flex items-center mt-1">
                                            {{ $usulan->lokasi_kegiatan ?? 'Lokasi tidak ditentukan' }}
                                        </div>
                                        <button @click="open = !open" class="text-xs text-indigo-500 mt-2 flex items-center hover:underline">
                                            <span x-text="open ? 'Tutup Rincian ↑' : 'Lihat Rincian & Perencanaan ↓'"></span>
                                        </button>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if($isSelesai)
                                            <span class="px-3 py-1 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-700 uppercase">
                                                Selesai ({{ Carbon::parse($dataSurat->created_at)->translatedFormat('d M Y') }})
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-[10px] font-bold rounded-full bg-amber-100 text-amber-700 uppercase">
                                                Belum Ada Surat
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex justify-center items-center gap-2">
                                            @if(!$isSelesai)
                                                {{-- Sesuaikan link create dengan parameter usulan_id --}}
                                                <a href="{{ route('persuratans.create', ['perencanaan_id' => $usulan->id]) }}"
                                                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition-all shadow-sm">
                                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                    Buat Surat
                                                </a>
                                            @else
                                                @if($dataSurat->file_pdf)
                                                    <a href="{{ Storage::url($dataSurat->file_pdf) }}" target="_blank"
                                                       class="p-2 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg hover:bg-emerald-600 hover:text-white transition-all shadow-sm"
                                                       title="Lihat PDF">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    </a>
                                                @endif

                                                <div class="flex items-center gap-2 border-l pl-2 border-gray-200">
                                                    <a href="{{ route('persuratans.edit', $dataSurat->id) }}" class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-500 hover:text-white transition-all shadow-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2a2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </a>
                                                    <button type="button" wire:click="delete({{ $dataSurat->id }})" wire:confirm="Hapus surat ini?" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Row Detail --}}
                                    <template x-if="open">
                                        <tr class="bg-gray-50">
                                            <td colspan="3" class="px-10 py-6 border-b border-gray-200">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                                    {{-- Kolom 1: Perencanaan Terkait --}}
                                                    <div>
                                                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-3">Data Perencanaan Terkait</h4>
                                                        <div class="space-y-3">
                                                            @forelse($usulan->perencanaans as $plan)
                                                                <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                                                                    <div class="text-[10px] font-mono font-bold text-indigo-500">{{ $plan->kode }}</div>
                                                                    <div class="text-xs font-semibold text-gray-700">{{ $plan->nama_komponen }}</div>
                                                                </div>
                                                            @empty
                                                                <span class="text-xs text-gray-400 italic">Tidak ada data perencanaan.</span>
                                                            @endforelse
                                                        </div>
                                                    </div>

                                                    {{-- Kolom 2: Pegawai --}}
                                                    <div>
                                                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-3">Pegawai Terlibat (Disetujui)</h4>
                                                        <div class="flex flex-wrap gap-2">
                                                            @forelse($usulan->usulanPegawais->where('status', 'approved') as $up)
                                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                                    <svg class="w-3 h-3 mr-1 text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                                                                    {{ $up->kepegawaian->nama ?? 'N/A' }}
                                                                </span>
                                                            @empty
                                                                <span class="text-xs text-gray-400 italic">Belum ada pegawai yang disetujui.</span>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <p class="italic">Data usulan tidak ditemukan...</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $usulans->links() }}
                </div>

            </div>
        </div>
    </div>
</div>