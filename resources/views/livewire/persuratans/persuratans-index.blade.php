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

                <div class="flex items-center justify-between mb-4">

                    {{-- Tombol tambah persuratan (opsional) --}}
                    <div>
                        <a href="{{ route('persuratans.create') }}"
                            class="inline-block px-4 py-2 rounded">Buat Surat</a>
                    </div>

                    {{-- Search bar --}}
                    <div>
                        <input type="text" wire:model.live="search"
                            placeholder="Cari surat..."
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
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 border">#</th>
                            <th class="px-4 py-2 border">Kepada</th>
                            <th class="px-4 py-2 border">Nama Surat</th>
                            <th class="px-4 py-2 border">Tanggal Upload</th>
                            <th class="px-4 py-2 border">Perihal</th>
                            <th class="px-4 py-2 border">Jenis Anggaran</th>
                            <th class="px-4 py-2 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($persurats as $idx => $p)
                        <tr>
                            <td class="px-4 py-2 border">{{ $idx + 1 }}</td>
                            <td class="px-4 py-2 border">{{ $p->pegawai->nama ?? '' }}</td>
                            <td class="px-4 py-2 border">{{ $p->nama_surat }}</td>
                            <td class="px-4 py-2 border">{{ $p->tanggal_upload ? (\Illuminate\Support\Carbon::parse($p->tanggal_upload)->format('d/m/Y')) : '' }}</td>
                            <td class="px-4 py-2 border">{{ $p->perihal ?? '' }}</td>
                            <td class="px-4 py-2 border">{{ $p->jenis_anggaran ?? '' }}</td>
                            <td class="px-4 py-2 border">-</td>
                        </tr>
                        @endforeach
                        @if ($persurats->isEmpty())
                        <tr>
                            <td colspan="7" class="px-4 py-2 border text-center text-gray-500">Tidak ada persuratan terkait.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                </div>

            </div>
        </div>
    </div>
</div>
