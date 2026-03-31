<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Pegawai') }}
    </h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">

                <div class="flex items-center justify-between mb-4">

                {{-- Tombol tambah kepegawaian (opsional) --}}
                    <div>
                        <a href="{{ route('kepegawaians.create') }}"
                            class="inline-block px-4 py-2 rounded">Tambah Pegawai </a>
                    </div>

                    {{-- Search bar --}}
                    <div>
                        <input type="text" wire:model.debounce.500ms="search"
                            placeholder="Cari pegawai..."
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
                                <th class="px-4 py-2 border">Nama</th>
                                <th class="px-4 py-2 border">NIP</th>
                                <th class="px-4 py-2 border">HP</th>
                                <th class="px-4 py-2 border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kepegawaians as $kepegawaian)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $kepegawaian->id }}</td>
                                    <td class="px-4 py-2 border">{{ $kepegawaian->nama }}</td>
                                    <td class="px-4 py-2 border">{{ $kepegawaian->nip }}</td>
                                    <td class="px-4 py-2 border">{{ $kepegawaian->hp }}</td>
                                    <td class="px-4 py-2 border">
                                        <a href="{{ route('kepegawaians.edit', $kepegawaian->id) }}"
    class="mr-2 text-blue-600">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-2 border text-center">
                                        Tidak ada Pegawai .
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $kepegawaians->links() }}
                </div>

            </div>
        </div>
    </div>
</div>