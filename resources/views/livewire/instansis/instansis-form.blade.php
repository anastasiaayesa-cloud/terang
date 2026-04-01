<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Manajemen Instansi') }}
    </h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 max-w-lg">
                <h2 class="text-xl mb-4">{{ $instansi_id ? 'Edit Instansi' : 'Tambah Instansi' }}</h2>                

                @if (session('success'))
                    <div class="mb-4 text-green-700">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="mb-4 text-red-700">{{ session('error') }}</div>
                @endif

                <form wire:submit.prevent="submit" autocomplete="off">
                    <div class="mb-3">
                        <label class="block mb-1">Nama Instansi *</label>
                        <input type="text" wire:model.defer="nama" class="border rounded px-3 py-2 w-full" placeholder="Isi instansi" autofocus>
                        @error('nama') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                      <div class="mb-3">
                        <label class="block mb-1">Alamat Instansi *</label>
                        <input type="text" wire:model.defer="alamat" class="border rounded px-3 py-2 w-full" placeholder="Isi alamat instansi" autofocus>
                        @error('alamat') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1">Telepon Instansi</label>
                        <input type="text" wire:model.defer="telp" class="border rounded px-3 py-2 w-full">
                        @error('telp') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1">Kabupaten</label>
                        <select wire:model="kabupaten_id" class="border rounded px-3 py-2 w-full">
                            <option value="">-- Pilih Kabupaten--</option>
                            @foreach ($kabupatenList as $kabupaten)
                                <option value="{{ $kabupaten->id }}">{{ $kabupaten->nama }}</option>
                            @endforeach
                        </select>
                        @error('kabupaten_id') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>


                    

                    <div class="flex items-center space-x-2">
                        <button type="submit" class=" px-4 py-2 rounded">
                            {{ $instansi_id ? 'Simpan Perubahan' : 'Simpan' }}
                        </button>
                        <a href="{{ route('instansis.index') }}" class="px-4 py-2 border rounded">Batal</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>