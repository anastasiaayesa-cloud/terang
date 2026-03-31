@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Manajemen Dokumen Perencanaan') }}
    </h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">

                <div class="flex items-center justify-between mb-4">

                    {{-- Tombol Upload Dokumen --}}
                    <div>
                        <a href="{{ route('dokumen-perencanaans.create') }}"
                            class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Upload Dokumen
                        </a>
                    </div>

                    {{-- Search bar --}}
                    <div>
                        <input type="text"
                            wire:model.debounce.300ms="search"
                            placeholder="Cari dokumen..."
                            class="border rounded px-3 py-2 w-64 focus:ring focus:ring-blue-200">
                    </div>
                </div>

                {{-- Pesan sukses/error --}}
                @if (session('success'))
                    <div class="mb-4 text-green-700">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="mb-4 text-red-700">{{ session('error') }}</div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 border text-left">#</th>
                                <th class="px-4 py-2 border text-left">Nama Dokumen</th>
                                <th class="px-4 py-2 border text-left">File</th>
                                <th class="px-4 py-2 border text-left">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dokumenperencanaans as $dok)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $dok->id }}</td>

                                    <td class="px-4 py-2 border">
                                        {{ $dok->nama }}
                                    </td>

                                    {{-- Link Download --}}
                                    <td class="px-4 py-2 border">
                                        @if ($dok->file_pdf)
                                            <a href="{{ Storage::url($dok->file_pdf) }}"
                                               class="text-blue-600 hover:underline"
                                               target="_blank">
                                                Download File
                                            </a>
                                        @else
                                            <span class="text-gray-500">Tidak ada file</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-2 border">
                                        {{ $dok->tanggal }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-2 border text-center text-gray-600">
                                        Tidak ada dokumen.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $dokumenperencanaans->links() }}
                </div>

            </div>
        </div>
    </div>
</div>