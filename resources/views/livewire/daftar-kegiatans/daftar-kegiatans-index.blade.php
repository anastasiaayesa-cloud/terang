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
                            <option value="">Semua Sumber</option>
                            <option value="usulan">Usulan</option>
                            <option value="tidak_ada_di_perencanaan">Tidak ada di Perencanaan</option>
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
                    <table class="min-w-full bg-white border">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border">#</th>
                                <th class="px-4 py-2 border">Nama Kegiatan</th>
                                <th class="px-4 py-2 border">Tujuan</th>
                                <th class="px-4 py-2 border">Waktu</th>
                                <th class="px-4 py-2 border">Keterangan</th>
                                <th class="px-4 py-2 border">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($daftarKegiatans as $kegiatan)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $kegiatan->id }}</td>
                                    <td class="px-4 py-2 border">{{ $kegiatan->nama_kegiatan }}</td>
                                    <td class="px-4 py-2 border">{{ $kegiatan->tujuan_kegiatan ?? '-' }}</td>
                                    <td class="px-4 py-2 border">
                                        {{ $kegiatan->waktu_kegiatan ? $kegiatan->waktu_kegiatan->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-2 border">{{ Str::limit($kegiatan->keterangan, 30) ?? '-' }}</td>
                                    <td class="px-4 py-2 border">
                                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $kegiatan->status_badge_class }}">
                                            {{ $kegiatan->status_label }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-2 border text-center">
                                        Tidak ada data kegiatan.
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
</div>
