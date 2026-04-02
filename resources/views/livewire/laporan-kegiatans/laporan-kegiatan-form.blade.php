<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">{{ $laporanKegiatan ? 'Edit Laporan' : 'Upload Laporan Kegiatan' }}</h2>
                        <a href="{{ route('laporan-kegiatans.index') }}" class="text-gray-600 hover:text-gray-900">← Kembali</a>
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
                                <label class="block text-sm font-medium text-gray-700">Perencanaan</label>
                                <select wire:model="perencanaan_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">-- Pilih Perencanaan --</option>
                                    @foreach ($perencanaanList as $perencanaan)
                                        <option value="{{ $perencanaan->id }}">{{ $perencanaan->nama_komponen }}</option>
                                    @endforeach
                                </select>
                                @error('perencanaan_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Judul Laporan</label>
                                <input type="text" wire:model="judul_laporan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('judul_laporan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Lokasi Kegiatan</label>
                                <input type="text" wire:model="lokasi_kegiatan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('lokasi_kegiatan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                                <input type="date" wire:model="tanggal_mulai" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('tanggal_mulai') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                                <input type="date" wire:model="tanggal_selesai" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('tanggal_selesai') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Deskripsi Kegiatan</label>
                                <textarea wire:model="deskripsi_kegiatan" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                @error('deskripsi_kegiatan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">File Laporan (PDF, max 5MB)</label>
                                <input type="file" wire:model="file_laporan" accept=".pdf" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('file_laporan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                @if ($laporanKegiatan && $laporanKegiatan->file_laporan)
                                    <p class="text-sm text-gray-500 mt-1">File saat ini: {{ $laporanKegiatan->file_laporan }}</p>
                                @endif
                                @if ($file_laporan)
                                    <p class="text-sm text-gray-500 mt-1">File baru: {{ $file_laporan->getClientOriginalName() }}</p>
                                @endif
                            </div>

                        </div>

                        <div class="mt-6 flex gap-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ $laporanKegiatan ? 'Update' : 'Upload' }}
                            </button>

                            @if ($laporanKegiatan)
                                <button type="button" wire:click="delete" wire:confirm="Yakin hapus laporan ini?" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
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
