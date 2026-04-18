<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">{{ $usulanPegawai ? 'Edit Pegawai' : 'Tambah Pegawai' }}</h2>
                        <a href="{{ route('usulan-pegawais.index') }}" class="text-gray-600 hover:text-gray-900">← Kembali</a>
                    </div>

                    @if (session()->has('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($usulan)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 mb-6">
                            <p class="text-sm text-gray-600">Menambahkan pegawai ke usulan:</p>
                            <p class="font-semibold text-lg">{{ $usulan->nama_kegiatan }}</p>
                            <p class="text-sm text-gray-600">{{ $usulan->tanggal_kegiatan->format('d/m/Y') }} • {{ $usulan->lokasi_kegiatan }}</p>
                        </div>
                    @endif

                    <form wire:submit="submit">
                        <div class="grid grid-cols-1 gap-6">

                            <input type="hidden" wire:model="usulan_id">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pegawai @if (count($pegawai_ids) > 0)<span class="text-blue-600 font-normal">({{ count($pegawai_ids) }} dipilih)</span>@endif
                                </label>
                                <div class="border rounded-md max-h-48 overflow-y-auto">
                                    @foreach ($pegawais as $pegawai)
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-50 cursor-pointer border-b last:border-b-0">
                                            <input type="checkbox" wire:model="pegawai_ids" value="{{ $pegawai->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm">{{ $pegawai->nama }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('pegawai_ids') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex gap-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ $usulanPegawai ? 'Update' : 'Simpan' }}
                            </button>

                            @if ($usulanPegawai)
                                <button type="button" wire:click="delete" wire:confirm="Yakin hapus usulan pegawai ini?" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
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
