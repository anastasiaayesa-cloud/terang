<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $bukti_id ? __('Edit Bukti Pengeluaran') : __('Upload Bukti Pengeluaran') }}
    </h2>
</x-slot>

<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-4">
            <a href="{{ route('bukti-pengeluarans.index') }}" 
               class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Bukti
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="mb-6 border-b pb-4">
                    <h3 class="text-lg font-bold text-gray-900">
                        {{ $bukti_id ? 'Formulir Perubahan Data' : 'Formulir Upload Baru' }}
                    </h3>
                    <p class="text-sm text-gray-600">
                        Pastikan data kegiatan dan nominal bukti sudah sesuai dengan dokumen fisik.
                    </p>
                </div>

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-lg border border-green-200 flex items-center">
                        <span class="mr-2">✅</span> {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-lg border border-red-200 flex items-center">
                        <span class="mr-2">⚠️</span> {{ session('error') }}
                    </div>
                @endif

                <form wire:submit.prevent="submit">
                    
                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Pilih Kegiatan & Komponen <span class="text-red-500">*</span>
                        </label>
                        <select 
                            wire:model="perencanaan_id"
                            class="border-gray-300 rounded-lg px-3 py-2.5 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm"
                            @disabled($bukti_id)
                        >
                            <option value="">-- Cari Nama Kegiatan / Komponen --</option>
                            @foreach ($perencanaanList as $p)
                                <option value="{{ $p->id }}">
                                    {{-- Menampilkan Nama Usulan (Kegiatan) + Nama Komponen --}}
                                    {{ strtoupper($p->usulan->nama_kegiatan ?? 'Tanpa Nama Kegiatan') }} 
                                    — [{{ $p->nama_komponen }}]
                                    @if($p->kode) ({{ $p->kode }}) @endif
                                </option>
                            @endforeach
                        </select>
                        
                        @error('perencanaan_id')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror

                        @if($bukti_id)
                            <div class="mt-2 flex items-center text-xs text-amber-600 bg-amber-50 p-2 rounded">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                Perencanaan tidak dapat diubah pada mode edit untuk menjaga integritas data.
                            </div>
                        @endif
                    </div>

                    <hr class="mb-6">

                    @if($existingFiles && count($existingFiles) > 0)
                    <div class="mb-8">
                        <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center">
                            <span class="bg-gray-100 p-1 rounded mr-2">📑</span> File Terdaftar
                        </h4>
                        <div class="space-y-4">
                            @foreach($existingFiles as $index => $bukti)
                            <div class="flex items-start space-x-3 p-4 border rounded-xl bg-gray-50 shadow-sm border-gray-200">
                                <div class="mt-1">
                                    <input 
                                        type="checkbox" 
                                        wire:model="keepFiles.{{ $bukti->id }}"
                                        class="h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                        title="Centang untuk tetap menyimpan file ini"
                                    >
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between border-b pb-2 mb-3">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-bold text-gray-700">{{ $bukti->file_name }}</span>
                                            <span class="text-xs bg-gray-200 px-2 py-0.5 rounded-full text-gray-600">{{ number_format($bukti->file_size / 1024 / 1024, 2) }} MB</span>
                                        </div>
                                        <a href="{{ asset('storage/'.$bukti->file_path) }}" target="_blank" class="text-xs text-blue-600 hover:underline">Lihat Dokumen ↗</a>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Tipe Bukti</label>
                                            <select 
                                                wire:model.defer="existingFiles.{{ $index }}.tipe_bukti"
                                                class="border-gray-300 rounded px-2 py-1.5 text-sm w-full focus:ring-blue-500"
                                            >
                                                <option value="tiket_pesawat">Tiket Pesawat</option>
                                                <option value="tiket_kapal">Tiket Kapal</option>
                                                <option value="tiket_kereta">Tiket Kereta</option>
                                                <option value="tiket_taxi">Tiket Taxi</option>
                                                <option value="tiket_hotel">Tiket Hotel</option>
                                                <option value="bukti_lainnya">Bukti Lainnya</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Nominal (Rp)</label>
                                            <input 
                                                type="number" 
                                                wire:model.defer="existingFiles.{{ $index }}.nominal"
                                                class="border-gray-300 rounded px-2 py-1.5 text-sm w-full focus:ring-blue-500"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Tanggal</label>
                                            <input 
                                                type="date" 
                                                wire:model.defer="existingFiles.{{ $index }}.tanggal_bukti"
                                                class="border-gray-300 rounded px-2 py-1.5 text-sm w-full focus:ring-blue-500"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Keterangan</label>
                                            <input 
                                                type="text" 
                                                wire:model.defer="existingFiles.{{ $index }}.keterangan"
                                                class="border-gray-300 rounded px-2 py-1.5 text-sm w-full focus:ring-blue-500"
                                                placeholder="Catatan tambahan..."
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-sm font-bold text-gray-800 flex items-center">
                                <span class="bg-blue-100 p-1 rounded mr-2 text-blue-600">📂</span> File Baru 
                                <span class="ml-2 px-2 py-0.5 bg-blue-600 text-white text-[10px] rounded-full">{{ count($files) }}</span>
                            </h4>
                            <button 
                                type="button"
                                wire:click="addFile"
                                class="text-xs font-bold bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg border border-blue-200 hover:bg-blue-100 transition"
                            >
                                + Tambah Baris File
                            </button>
                        </div>

                        @if(count($files) > 0)
                        <div class="space-y-4">
                            @foreach($files as $index => $fileData)
                            <div class="p-5 border border-blue-200 rounded-xl bg-blue-50/50 shadow-sm relative">
                                <button 
                                    type="button"
                                    wire:click="removeFile({{ $index }})"
                                    class="absolute top-4 right-4 text-red-400 hover:text-red-600"
                                    title="Hapus baris ini"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2 border-b border-blue-100 pb-2 mb-1">
                                        <span class="text-xs font-bold text-blue-700 uppercase tracking-wider">Data Lampiran #{{ $index + 1 }}</span>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Pilih Dokumen (PDF/JPG/PNG) <span class="text-red-500">*</span></label>
                                        <input 
                                            type="file" 
                                            wire:model="files.{{ $index }}.file"
                                            accept=".pdf,.jpg,.jpeg,.png"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer"
                                        >
                                        @if($fileData['file'])
                                            <p class="text-[10px] text-green-600 mt-1 font-medium italic">✓ {{ $fileData['file']->getClientOriginalName() }}</p>
                                        @endif
                                        @error("files.$index.file") <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Tipe Bukti <span class="text-red-500">*</span></label>
                                        <select 
                                            wire:model="files.{{ $index }}.tipe_bukti"
                                            class="border-gray-300 rounded-lg px-3 py-1.5 text-sm w-full focus:ring-blue-500"
                                        >
                                            <option value="">-- Pilih Tipe --</option>
                                            <option value="tiket_pesawat">Tiket Pesawat</option>
                                            <option value="tiket_kapal">Tiket Kapal</option>
                                            <option value="tiket_kereta">Tiket Kereta</option>
                                            <option value="tiket_taxi">Tiket Taxi</option>
                                            <option value="tiket_hotel">Tiket Hotel</option>
                                            <option value="bukti_lainnya">Bukti Lainnya</option>
                                        </select>
                                        @error("files.$index.tipe_bukti") <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Nominal (Rp) <span class="text-red-500">*</span></label>
                                        <input 
                                            type="number" 
                                            wire:model="files.{{ $index }}.nominal"
                                            class="border-gray-300 rounded-lg px-3 py-1.5 text-sm w-full focus:ring-blue-500"
                                            placeholder="Contoh: 500000"
                                        >
                                        @error("files.$index.nominal") <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Tanggal Transaksi</label>
                                        <input 
                                            type="date" 
                                            wire:model="files.{{ $index }}.tanggal_bukti"
                                            class="border-gray-300 rounded-lg px-3 py-1.5 text-sm w-full focus:ring-blue-500"
                                        >
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Keterangan Singkat</label>
                                        <input 
                                            type="text" 
                                            wire:model="files.{{ $index }}.keterangan"
                                            class="border-gray-300 rounded-lg px-3 py-1.5 text-sm w-full focus:ring-blue-500"
                                            placeholder="Contoh: Tiket pesawat keberangkatan tim..."
                                        >
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-10 bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl">
                            <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <p class="text-sm text-gray-500">Belum ada file baru yang ditambahkan.</p>
                            <button type="button" wire:click="addFile" class="mt-3 text-blue-600 text-xs font-bold hover:underline underline-offset-4">+ Klik di sini untuk menambah</button>
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-end space-x-3 border-t pt-6">
                        <a href="{{ route('bukti-pengeluarans.index') }}"
                           class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button 
                            type="submit"
                            class="bg-blue-600 text-white px-8 py-2.5 text-sm font-bold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-200 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled"
                            @disabled(count($files) === 0 && count($existingFiles ?? []) === 0)
                        >
                            <span wire:loading.remove>{{ $bukti_id ? 'Simpan Perubahan' : 'Upload & Proses Semua' }}</span>
                            <span wire:loading>Memproses...</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>