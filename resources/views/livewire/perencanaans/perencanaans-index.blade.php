<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Perencanaan') }}
    </h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">

                <div class="flex items-center justify-between mb-4">
                    <div>
                        <a href="{{ route('perencanaans.create') }}"
                            class="inline-block px-4 py-2 rounded">Tambah Perencanaan</a>
                    </div>

                    <div>
                        <input type="text" wire:model.debounce.500ms="search"
                            placeholder="Cari perencanaan..."
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
                                <th class="px-4 py-2 border">Kode</th>
                                <th class="px-4 py-2 border">Nama Komponen</th>
                                <th class="px-4 py-2 border">Dokumen</th>
                                <th class="px-4 py-2 border">Bukti</th>
                                <th class="px-4 py-2 border">Total Bukti</th>
                                <th class="px-4 py-2 border">Usulan 30%</th>
                                <th class="px-4 py-2 border">Grand Total</th>
                                <th class="px-4 py-2 border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($perencanaans as $perencanaan)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $perencanaan->id }}</td>
                                    <td class="px-4 py-2 border">{{ $perencanaan->kode }}</td>
                                    <td class="px-4 py-2 border">{{ $perencanaan->nama_komponen }}</td>
                                    <td class="px-4 py-2 border">
                                        {{ $perencanaan->dokumenPerencanaan?->nama ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2 border text-center">
                                        <a href="{{ route('bukti-pengeluarans.index') }}"
                                           class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded hover:bg-blue-100 text-sm">
                                            📄 {{ $perencanaan->buktiPengeluarans->count() }} File
                                        </a>
                                    </td>
                                    <td class="px-4 py-2 border text-right font-medium">
                                        Rp {{ number_format($perencanaan->buktiPengeluarans->sum('nominal'), 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 border text-right font-medium text-orange-700">
                                        Rp {{ number_format($perencanaan->usulanPembayarans->sum('total_nominal'), 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 border text-right font-bold text-green-700">
                                        Rp {{ number_format($perencanaan->buktiPengeluarans->sum('nominal') + $perencanaan->usulanPembayarans->sum('total_nominal'), 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <a href="{{ route('perencanaans.edit', $perencanaan->id) }}"
                                           class="mr-2 text-blue-600">Edit</a>
                                        <a href="{{ route('bukti-pengeluarans.index') }}"
                                           class="text-green-600">Bukti</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-2 border text-center">
                                        Tidak ada data perencanaan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $perencanaans->links() }}
                </div>

            </div>
        </div>
    </div>
</div>
