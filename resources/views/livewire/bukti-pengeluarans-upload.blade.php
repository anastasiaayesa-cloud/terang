<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">Upload Bukti Pengeluaran</h2>
        <a href="{{ route('bukti-pengeluarans.index') }}" class="text-blue-600 hover:underline text-sm">← Kembali ke Daftar Bukti</a>
    </div>

    <form wire:submit.prevent="submit" class="space-y-6">
        
        {{-- 1. Pilih Perencanaan --}}
        <div class="bg-white p-4 rounded-lg shadow-sm border">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Perencanaan <span class="text-red-500">*</span></label>
            <select wire:model.live="perencanaan_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Pilih Perencanaan --</option>
                @foreach($perencanaanList as $p)
                    <option value="{{ $p->id }}">{{ $p->nama_komponen }} ({{ $p->kode_komponen }})</option>
                @endforeach
            </select>
            @error('perencanaan_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        {{-- 2. Area Upload File --}}
        <div class="border-2 border-dashed border-blue-200 p-8 rounded-lg text-center bg-blue-50 relative">
            {{-- Kita menggunakan index dinamis berdasarkan jumlah file yang sudah ada --}}
            <input type="file" 
                   wire:model="files.{{ count($files) }}.file" 
                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                   id="fileInput" 
                   accept=".pdf,.jpg,.jpeg,.png">
            
            <div class="flex flex-col items-center pointer-events-none">
                <svg class="w-12 h-12 text-blue-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <p class="text-sm text-blue-600 font-bold">Klik atau seret file ke sini untuk menambah bukti</p>
                <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (Maks. 5MB per file)</p>
            </div>
            
            {{-- Indikator Loading Upload (SANGAT PENTING) --}}
            <div wire:loading wire:target="files" class="mt-4">
                <div class="flex items-center justify-center text-blue-600 font-bold text-sm">
                    <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sedang memproses file, tunggu sebentar...
                </div>
            </div>
        </div>

        {{-- 3. Daftar File yang Akan Diupload --}}
        @if(count($files) > 0)
            <div class="space-y-4">
                <h3 class="font-bold text-gray-800 flex items-center">
                    📂 File Baru ({{ count($files) }})
                </h3>
                
                @foreach($files as $index => $fileData)
                    <div class="bg-white border-l-4 border-blue-500 rounded-r-lg p-5 shadow-sm relative transition-all" wire:key="file-row-{{ $index }}">
                        <button type="button" wire:click="removeFile({{ $index }})" class="absolute top-3 right-4 text-gray-400 hover:text-red-500 transition">
                            <span class="text-xs font-bold">Hapus</span>
                        </button>
                        
                        <div class="mb-4 flex items-center">
                            @if(isset($fileData['file']))
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-[10px] font-mono mr-2">READY</span>
                                <span class="text-sm font-semibold text-gray-700 truncate max-w-xs">{{ $fileData['file']->getClientOriginalName() }}</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-[10px] font-mono mr-2">UPLOADING...</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            {{-- Tipe Bukti --}}
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Tipe Bukti *</label>
                                <select wire:model.live="files.{{ $index }}.tipe_bukti" class="block w-full text-sm border-gray-300 rounded-md shadow-sm">
                                    <option value="">-- Pilih --</option>
                                    <option value="tiket_pesawat">Tiket Pesawat</option>
                                    <option value="tiket_taxi">Tiket Taxi</option>
                                    <option value="tiket_hotel">Hotel</option>
                                    <option value="bukti_lainnya">Lainnya</option>
                                </select>
                                @error("files.{$index}.tipe_bukti") <span class="text-red-500 text-[10px] italic">{{ $message }}</span> @enderror
                            </div>

                            {{-- Nominal --}}
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Nominal *</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 text-xs">Rp</span>
                                    <input type="number" wire:model.live="files.{{ $index }}.nominal" class="block w-full pl-8 text-sm border-gray-300 rounded-md shadow-sm" placeholder="0">
                                </div>
                                @error("files.{$index}.nominal") <span class="text-red-500 text-[10px] italic">{{ $message }}</span> @enderror
                            </div>

                            {{-- Tanggal --}}
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Tanggal</label>
                                <input type="date" wire:model.live="files.{{ $index }}.tanggal_bukti" class="block w-full text-sm border-gray-300 rounded-md shadow-sm">
                            </div>

                            {{-- Keterangan --}}
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Keterangan</label>
                                <input type="text" wire:model.live="files.{{ $index }}.keterangan" class="block w-full text-sm border-gray-300 rounded-md shadow-sm" placeholder="Catatan...">
                            </div>
                        </div>

                        @error("files.{$index}.file") <p class="text-red-500 text-[10px] mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>
                @endforeach
            </div>
        @endif

        {{-- 4. Tombol Submit --}}
        <div class="pt-6 border-t">
            <button type="submit" 
                    wire:loading.attr="disabled" 
                    wire:target="files, submit"
                    class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg transition-all flex items-center justify-center">
                
                <span wire:loading.remove wire:target="submit">🚀 SIMPAN SEMUA BUKTI</span>
                <span wire:loading wire:target="submit" class="flex items-center">
                    <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">...</svg>
                    PROSES MENYIMPAN...
                </span>
            </button>
            <p class="text-center text-[10px] text-gray-400 mt-2 italic">Pastikan indikator "READY" muncul sebelum menekan tombol simpan.</p>
        </div>

    </form>
</div>