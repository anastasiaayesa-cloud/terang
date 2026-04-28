<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Manajemen Instansi') }}
    </h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">

                <div class="flex items-center justify-between mb-4">

                    {{-- Tombol tambah perencaan (opsional) --}}
                    <div>
                        <a href="{{ route('instansis.create') }}"
                            class="inline-block px-4 py-2 rounded">Tambah Instansi </a>
                    </div>

                    {{-- Search bar --}}
                    <div>
                        <input type="text" wire:model.live="search"
                            placeholder="Cari instansi..."
                            class="border rounded px-3 py-2 w-64 focus:ring focus:ring-blue-200">
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-4 text-green-700">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="mb-4 text-red-700">{{ session('error') }}</div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border">#</th>
                                {{-- <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('komponen')">
                                    Komponen
                                    @if($sortField === 'komponen')
                                        @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                    @endif
                                </th> --}}
                                <th class="px-4 py-2 border">Nama Instansi</th>
                                <th class="px-4 py-2 border">Alamat Instansi</th>
                                <th class="px-4 py-2 border">Telpon Instansi</th>
                                <th class="px-4 py-2 border">Kabupaten</th>
                                <th class="px-4 py-2 border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($instansis as $instansi)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $instansi->id }}</td>
                                    <td class="px-4 py-2 border">{{ $instansi->nama }}</td>
                                    <td class="px-4 py-2 border">{{ $instansi->alamat }}</td>
                                    <td class="px-4 py-2 border">{{ $instansi->telp }}</td>
                                    <td class="px-4 py-2 border">{{ $instansi->kabupaten?->nama ?? 'Tidak ada kabupaten' }}</td>
                                    <td class="px-4 py-2 border">
                                        <a href="{{ route('instansis.edit', $instansi->id) }}"
                                        class="mr-2 text-blue-600">Edit</a>
                                    </td>
                                    
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center px-4 py-2 border">Data belum tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $instansis->links() }}
                </div>

            </div>
        </div>
    </div>
</div>