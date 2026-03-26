<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Manajemen Dokumen Perencanaan') }}
    </h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 max-w-lg">

                <h2 class="text-xl mb-4 font-semibold">
                    {{ $dokumenperencanaan_id ? 'Edit Dokumen' : 'Tambah Dokumen' }}
                </h2>

                {{-- Flash messages --}}
                @if (session('success'))
                    <div class="mb-4 text-green-700">{{ session('success') }}</div>
                @endif

                <form wire:submit.prevent="submit" autocomplete="off">

                    {{-- NAMA --}}
                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Nama *</label>
                        <input type="text" wire:model.defer="nama"
                               class="border rounded px-3 py-2 w-full"
                               placeholder="Isi Nama Dokumen" autofocus>

                        @error('nama') 
                            <span class="text-red-600 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>

                    {{-- FILE PDF --}}
                    <div class="mb-4">
                        <label class="block mb-1 font-medium">File PDF *</label>
                        <input type="file" wire:model="file_pdf" accept="application/pdf"
                               class="border rounded px-3 py-2 w-full">

                        @error('file_pdf') 
                            <span class="text-red-600 text-sm">{{ $message }}</span> 
                        @enderror

                        {{-- PREVIEW FILE LAMA --}}
                        @if ($dokumenperencanaan_id && $file_pdf_old)
                            <p class="mt-2 text-sm">
                                File lama: 
                                <a href="{{ asset('storage/'.$file_pdf_old) }}" 
                                   target="_blank" 
                                   class="text-blue-600 underline">
                                   Lihat Dokumen
                                </a>
                            </p>
                        @endif
                    </div>

                    {{-- TANGGAL --}}
                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Tanggal *</label>
                        <input type="date" wire:model="tanggal"
                               class="border rounded px-3 py-2 w-full">

                        @error('tanggal') 
                            <span class="text-red-600 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>

                    {{-- BUTTON ACTION --}}
                    <div class="flex items-center space-x-2">

                        <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded">
                            {{ $dokumenperencanaan_id ? 'Simpan Perubahan' : 'Simpan' }}
                        </button>

                        @if ($dokumenperencanaan_id)
                            <button type="button" 
                                    wire:click="delete" 
                                    class="px-4 py-2 bg-red-600 text-white rounded">
                                Hapus
                            </button>
                        @endif

                        <a href="{{ route('dokumen-perencanaans.index') }}"
                           class="px-4 py-2 border rounded">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>