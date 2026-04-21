<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 text-gray-900">

                    {{-- Header --}}
                    <div class="flex justify-between items-center mb-6 border-b pb-4">
                        <h2 class="text-2xl font-bold text-gray-800">
                            {{ $laporanKegiatan ? '📝 Edit Laporan' : '📤 Upload Laporan Kegiatan' }}
                        </h2>
                        <a href="{{ route('laporan-kegiatans.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition">
                            ← Kembali ke Daftar
                        </a>
                    </div>

                    {{-- Alert Berhasil --}}
                    @if (session()->has('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-6 shadow-sm">
                            <div class="flex">
                                <div class="py-1"><svg class="h-6 w-6 text-green-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                                <div><p class="font-bold">Berhasil!</p><p class="text-sm">{{ session('success') }}</p></div>
                            </div>
                        </div>
                    @endif

                    <form wire:submit.prevent="submit">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Dropdown Pegawai --}}
                            <div class="space-y-1">
                                <label class="block text-sm font-semibold text-gray-700">Pegawai</label>
                                <select wire:model="pegawai_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('pegawai_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Pegawai --</option>
                                    @foreach ($pegawaiList as $pegawai)
                                        <option value="{{ $pegawai->id }}">{{ $pegawai->nama }}</option>
                                    @endforeach
                                </select>
                                @error('pegawai_id') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                            </div>

                            {{-- Dropdown Usulan (PENTING: Agar auto-fill bekerja) --}}
                            <div class="space-y-1">
                                <label class="block text-sm font-semibold text-gray-700">Usulan Kegiatan</label>
                                <select wire:model="usulan_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('usulan_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Usulan --</option>
                                    @foreach ($usulanList as $usulan)
                                        <option value="{{ $usulan->id }}">{{ $usulan->nama_kegiatan }}</option>
                                    @endforeach
                                </select>
                                @error('usulan_id') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                            </div>

                            {{-- Dropdown Perencanaan --}}
                            {{-- Bagian ini disembunyikan karena perencanaan_id bersifat nullable --}}
                            {{-- 
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Perencanaan / Komponen</label>
                                <select wire:model="perencanaan_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">-- Pilih Perencanaan --</option>
                                    @foreach($perencanaanList as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_komponen }}</option>
                                    @endforeach
                                </select>
                            </div> 
                            --}}

                            {{-- Judul Laporan --}}
                            <div class="space-y-1">
                                <label class="block text-sm font-semibold text-gray-700">Judul Laporan</label>
                                <input type="text" wire:model="judul_laporan" placeholder="Masukkan judul laporan..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('judul_laporan') border-red-500 @enderror">
                                @error('judul_laporan') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                            </div>

                            {{-- Lokasi --}}
                            <div class="space-y-1">
                                <label class="block text-sm font-semibold text-gray-700">Lokasi Kegiatan</label>
                                <input type="text" wire:model="lokasi_kegiatan" placeholder="Contoh: Kantor BPMP..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('lokasi_kegiatan') border-red-500 @enderror">
                                @error('lokasi_kegiatan') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                            </div>

                            {{-- Tanggal Mulai --}}
                            <div class="space-y-1">
                                <label class="block text-sm font-semibold text-gray-700">Tanggal Mulai</label>
                                <input type="date" wire:model="tanggal_mulai" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('tanggal_mulai') border-red-500 @enderror">
                                @error('tanggal_mulai') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                            </div>

                            {{-- Tanggal Selesai --}}
                            <div class="space-y-1">
                                <label class="block text-sm font-semibold text-gray-700">Tanggal Selesai</label>
                                <input type="date" wire:model="tanggal_selesai" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('tanggal_selesai') border-red-500 @enderror">
                                @error('tanggal_selesai') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div class="md:col-span-2 space-y-1">
                                <label class="block text-sm font-semibold text-gray-700">Deskripsi Kegiatan</label>
                                <textarea wire:model="deskripsi_kegiatan" rows="3" placeholder="Jelaskan ringkasan kegiatan..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('deskripsi_kegiatan') border-red-500 @enderror"></textarea>
                                @error('deskripsi_kegiatan') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                            </div>

                            {{-- File Laporan --}}
                            <div class="md:col-span-2 p-4 bg-gray-50 rounded-lg border border-dashed border-gray-300 @error('file_laporan') border-red-500 bg-red-50 @enderror">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">File Laporan (PDF, Max 5MB)</label>
                                <input type="file" wire:model="file_laporan" accept=".pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                
                                <div wire:loading wire:target="file_laporan" class="mt-2 text-blue-600 font-bold text-xs flex items-center">
                                    <svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Sedang mengunggah file, mohon tunggu...
                                </div>

                                @error('file_laporan') <span class="text-red-500 text-xs italic block mt-1">{{ $message }}</span> @enderror

                                {{-- Info File --}}
                                <div class="mt-3 flex flex-col gap-1">
                                    @if ($laporanKegiatan && $laporanKegiatan->file_laporan)
                                        <p class="text-[10px] text-gray-500 italic">📄 File saat ini: <span class="font-mono text-blue-600">{{ basename($laporanKegiatan->file_laporan) }}</span></p>
                                    @endif
                                    @if ($file_laporan)
                                        <p class="text-[10px] text-green-600 font-bold italic">✅ Siap diunggah: {{ $file_laporan->getClientOriginalName() }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="mt-8 flex items-center gap-4">
                            <button type="submit" 
                                    wire:loading.attr="disabled" 
                                    wire:target="file_laporan, submit"
                                    class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-md font-bold text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="submit">
                                    {{ $laporanKegiatan ? '💾 Perbarui Laporan' : '🚀 Upload Laporan' }}
                                </span>
                                <span wire:loading wire:target="submit" class="flex items-center">
                                    <svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Menyimpan...
                                </span>
                            </button>

                            @if ($laporanKegiatan)
                                <button type="button" 
                                        wire:click="delete" 
                                        wire:confirm="Hapus laporan ini secara permanen?" 
                                        class="px-6 py-2 bg-red-100 text-red-700 font-bold rounded-md hover:bg-red-200 transition">
                                    🗑️ Hapus
                                </button>
                            @endif
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>