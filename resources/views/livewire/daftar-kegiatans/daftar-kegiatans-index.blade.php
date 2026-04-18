<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Daftar Kegiatan') }}
    </h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">

                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <select wire:model="filterStatus"
                            class="border rounded px-3 py-2 focus:ring focus:ring-blue-200">
                            <option value="">Semua Status</option>
                            <option value="usulan">Usulan</option>
                            <option value="perencanaan">Perencanaan</option>
                        </select>
                    </div>

                    <div>
                        <input type="text" wire:model.debounce.500ms="search"
                            placeholder="Cari kegiatan..."
                            class="border rounded px-3 py-2 w-64 focus:ring focus:ring-blue-200">
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-4 text-green-700">{{ session('success') }}</div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 border text-left text-xs font-semibold text-gray-600 uppercase">#</th>
                                <th class="px-4 py-3 border text-left text-xs font-semibold text-gray-600 uppercase">Kegiatan & Komponen</th>
                                <th class="px-4 py-3 border text-left text-xs font-semibold text-gray-600 uppercase">Tujuan</th>
                                <th class="px-4 py-3 border text-left text-xs font-semibold text-gray-600 uppercase">Waktu</th>
                                <th class="px-4 py-3 border text-left text-xs font-semibold text-gray-600 uppercase">Keterangan</th>
                                <th class="px-4 py-3 border text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                <th class="px-4 py-3 border text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($daftarKegiatans as $index => $kegiatan)
                                <tr>
                                    <td class="px-4 py-4 border text-sm text-gray-500">
                                        {{ $daftarKegiatans->firstItem() + $index }}
                                    </td>
                                    <td class="px-4 py-4 border">
                                        <div class="flex flex-col">
                                            {{-- Menampilkan Nama Kegiatan (dari Usulan) --}}
                                            <div class="text-sm font-bold text-gray-900 leading-tight">
                                                @if($kegiatan->nama_kegiatan)
                                                    {{ $kegiatan->nama_kegiatan }}
                                                @else
                                                    <span class="text-gray-400 font-normal italic">Kegiatan Mandiri</span>
                                                @endif
                                            </div>

                                            {{-- Menampilkan Nama Komponen di bawahnya --}}
                                            <div class="mt-1.5 flex items-center">
                                                <span class="bg-blue-50 text-blue-700 text-[10px] px-1.5 py-0.5 rounded border border-blue-100 uppercase font-bold mr-2">
                                                    Komponen
                                                </span>
                                                <span class="text-xs text-gray-600">
                                                    {{ $kegiatan->nama_komponen ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 border text-sm text-gray-600">
                                        {{ $kegiatan->tujuan_kegiatan ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 border text-sm text-gray-600">
                                        {{ $kegiatan->waktu_kegiatan ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 border text-sm text-gray-600 italic">
                                        {{ $kegiatan->keterangan ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 border">
                                        <span class="inline-flex px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $kegiatan->status === 'usulan' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $kegiatan->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 border text-center">
                                        <button 
                                            wire:click="$dispatch('openEditModal', { id: {{ $kegiatan->id }} })"
                                            class="inline-flex items-center px-3 py-1 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            Lengkapi
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 border text-center text-gray-500">
                                        Tidak ada data kegiatan ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $daftarKegiatans->links() }}
                </div>

            </div>
        </div>
    </div>
    @livewire('DaftarKegiatans.DaftarKegiatansForm')
</div>
