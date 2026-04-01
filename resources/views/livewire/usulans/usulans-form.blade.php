<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">{{ $usulan ? 'Edit Usulan' : 'Tambah Usulan' }}</h2>
                        <a href="{{ route('usulans.index') }}" class="text-gray-600 hover:text-gray-900">← Kembali</a>
                    </div>

                    @if (session()->has('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form wire:submit="submit">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pegawai</label>
                                <select wire:model="pegawai_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">-- Pilih Pegawai --</option>
                                    @foreach ($pegawaiList as $pegawai)
                                        <option value="{{ $pegawai->pegawai_id }}">{{ $pegawai->nama }}</option>
                                    @endforeach
                                </select>
                                @error('pegawai_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Kegiatan</label>
                                <input type="text" wire:model="nama_kegiatan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('nama_kegiatan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Kegiatan</label>
                                <input type="date" wire:model="tanggal_kegiatan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('tanggal_kegiatan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Lokasi Kegiatan</label>
                                <input type="text" wire:model="lokasi_kegiatan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('lokasi_kegiatan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea wire:model="deskripsi" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                @error('deskripsi') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                        </div>

                        <div class="mt-6 flex gap-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ $usulan ? 'Update' : 'Simpan' }}
                            </button>

                            @if ($usulan)
                                <button type="button" wire:click="delete" wire:confirm="Yakin hapus usulan ini?" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Hapus
                                </button>
                            @endif
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
