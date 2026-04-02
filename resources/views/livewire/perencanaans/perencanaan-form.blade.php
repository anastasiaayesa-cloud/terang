<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Manajemen Perencanaan') }}
    </h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 max-w-lg">

                <h2 class="text-xl mb-4 font-semibold">
                    {{ $perencanaan_id ? 'Edit Perencanaan' : 'Tambah Perencanaan' }}
                </h2>

                @if (session('success'))
                    <div class="mb-4 text-green-700">{{ session('success') }}</div>
                @endif

                <form wire:submit.prevent="submit" autocomplete="off">

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Kode</label>
                        <input type="text" wire:model.defer="kode"
                               class="border rounded px-3 py-2 w-full"
                               placeholder="Isi Kode Perencanaan" autofocus>

                        @error('kode')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Nama Komponen *</label>
                        <input type="text" wire:model.defer="nama_komponen"
                               class="border rounded px-3 py-2 w-full"
                               placeholder="Isi Nama Komponen">

                        @error('nama_komponen')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Berdasarkan Usulan</label>
                        <select wire:model.defer="usulan_id"
                                class="border rounded px-3 py-2 w-full">
                            <option value="">-- Mandiri (Tidak dari Usulan) --</option>
                            @foreach ($usulans as $usulan)
                                <option value="{{ $usulan->id }}">
                                    {{ $usulan->nama_kegiatan }}
                                </option>
                            @endforeach
                        </select>

                        @error('usulan_id')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Dokumen Perencanaan *</label>
                        <select wire:model.defer="dokumen_perencanaan_id"
                                class="border rounded px-3 py-2 w-full">
                            <option value="">-- Pilih Dokumen --</option>
                            @foreach ($dokumenPerencanaans as $dokumen)
                                <option value="{{ $dokumen->id }}">
                                    {{ $dokumen->nama }}
                                </option>
                            @endforeach
                        </select>

                        @error('dokumen_perencanaan_id')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex items-center space-x-2">
                        <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded">
                            {{ $perencanaan_id ? 'Simpan Perubahan' : 'Simpan' }}
                        </button>

                        <a href="{{ route('perencanaans.index') }}"
                           class="px-4 py-2 border rounded">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
