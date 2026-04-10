<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Daftar Usulan</h2>
                        <a href="{{ route('usulans.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Tambah Usulan
                        </a>
                    </div>

                    <div class="flex gap-4 mb-4">
                        <input type="text" wire:model.live="search" placeholder="Cari nama kegiatan..." class="border rounded px-3 py-2 w-64">
                    </div>

                    @if (session()->has('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Kegiatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($usulans as $usulan)
                                <tr wire:key="{{ $usulan->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $usulan->nama_kegiatan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $usulan->tanggal_kegiatan->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $usulan->lokasi_kegiatan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('usulans.edit', $usulan->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">✏️</a>
                                        <button wire:click="delete({{ $usulan->id }})" wire:confirm="Yakin hapus usulan ini?" class="text-red-600 hover:text-red-900">🗑️</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada data usulan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $usulans->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
