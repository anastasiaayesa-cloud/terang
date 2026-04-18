<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Upload Bukti Pengeluaran') }}
    </h2>
</x-slot>

<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <div class="mb-4">
            <a href="{{ route('bukti-pengeluarans.index') }}" 
               class="text-blue-600 hover:text-blue-800">
                ← Kembali ke Daftar Bukti
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-2">
                    {{ $bukti_id ? 'Edit Bukti Pengeluaran' : 'Upload Bukti Pengeluaran' }}
                </h3>
                <p class="text-sm text-gray-600 mb-6">
                    Upload bukti pengeluaran untuk perencanaan yang dipilih
                </p>

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-50 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-50 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form wire:submit.prevent="submit">
                    
                    <!-- Pilih Perencanaan -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Perencanaan <span class="text-red-500">*</span>
                        </label>
                        <select 
                            wire:model="perencanaan_id"
                            class="border rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            @disabled($bukti_id)
                        >
                            <option value="">-- Pilih Perencanaan --</option>
                            @foreach ($perencanaanList as $p)
                                <option value="{{ $p->id }}">
                                    {{ $p->nama_komponen }}
                                    @if($p->kode)
                                        ({{ $p->kode }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('perencanaan_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if($bukti_id)
                            <p class="mt-1 text-xs text-gray-500">
                                Perencanaan tidak dapat diubah saat edit mode
                            </p>
                        @endif
                    </div>

                    <!-- Existing Files (Edit Mode) -->
                    @if($existingFiles && count($existingFiles) > 0)
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">
                            File Existing
                        </h4>
                        <div class="space-y-3">
                            @foreach($existingFiles as $bukti)
                            <div class="flex items-start space-x-3 p-3 border rounded-lg bg-gray-50">
                                <input 
                                    type="checkbox" 
                                    wire:model="keepFiles.{{ $bukti->id }}"
                                    class="mt-1 h-4 w-4 text-blue-600 rounded"
                                >
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-medium">{{ $bukti->file_name }}</span>
                                            <span class="text-xs text-gray-500">({{ number_format($bukti->file_size / 1024 / 1024, 2) }} MB)</span>
                                        </div>
                                    </div>
                                    <div class="mt-2 grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Tipe Bukti</label>
                                            <select 
                                                wire:model.defer="existingFiles.{{ $loop->index }}.tipe_bukti"
                                                class="border rounded px-2 py-1 text-sm w-full"
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
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Nominal</label>
                                            <input 
                                                type="number" 
                                                wire:model.defer="existingFiles.{{ $loop->index }}.nominal"
                                                class="border rounded px-2 py-1 text-sm w-full"
                                                placeholder="0"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label>
                                            <input 
                                                type="date" 
                                                wire:model.defer="existingFiles.{{ $loop->index }}.tanggal_bukti"
                                                class="border rounded px-2 py-1 text-sm w-full"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Keterangan</label>
                                            <input 
                                                type="text" 
                                                wire:model.defer="existingFiles.{{ $loop->index }}.keterangan"
                                                class="border rounded px-2 py-1 text-sm w-full"
                                                placeholder="Optional"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- New Files List -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-medium text-gray-700">
                                File Baru ({{ count($files) }})
                            </h4>
                            <button 
                                type="button"
                                wire:click="addFile"
                                class="text-sm text-blue-600 hover:text-blue-800"
                            >
                                + Tambah File
                            </button>
                        </div>

                        @if(count($files) > 0)
                        <div class="space-y-4">
                            @foreach($files as $index => $fileData)
                            <div class="p-4 border rounded-lg bg-blue-50">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-sm font-medium">File #{{ $index + 1 }}</span>
                                    <button 
                                        type="button"
                                        wire:click="removeFile({{ $index }})"
                                        class="text-red-600 hover:text-red-800 text-sm"
                                    >
                                        Hapus
                                    </button>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">File *</label>
                                        <input 
                                            type="file" 
                                            wire:model="files.{{ $index }}.file"
                                            accept=".pdf,.jpg,.jpeg,.png"
                                            class="border rounded px-2 py-1 text-sm w-full"
                                        >
                                        @if($fileData['file'])
                                            <p class="text-xs text-green-600 mt-1">
                                                ✓ {{ $fileData['file']->getClientOriginalName() }}
                                            </p>
                                        @endif
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Tipe Bukti *</label>
                                        <select 
                                            wire:model="files.{{ $index }}.tipe_bukti"
                                            class="border rounded px-2 py-1 text-sm w-full"
                                        >
                                            <option value="">-- Pilih Tipe --</option>
                                            <option value="tiket_pesawat">Tiket Pesawat</option>
                                            <option value="tiket_kapal">Tiket Kapal</option>
                                            <option value="tiket_kereta">Tiket Kereta</option>
                                            <option value="tiket_taxi">Tiket Taxi</option>
                                            <option value="tiket_hotel">Tiket Hotel</option>
                                            <option value="bukti_lainnya">Bukti Lainnya</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Nominal *</label>
                                        <input 
                                            type="number" 
                                            wire:model="files.{{ $index }}.nominal"
                                            class="border rounded px-2 py-1 text-sm w-full"
                                            placeholder="0"
                                            min="0"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label>
                                        <input 
                                            type="date" 
                                            wire:model="files.{{ $index }}.tanggal_bukti"
                                            class="border rounded px-2 py-1 text-sm w-full"
                                        >
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Keterangan</label>
                                    <input 
                                        type="text" 
                                        wire:model="files.{{ $index }}.keterangan"
                                        class="border rounded px-2 py-1 text-sm w-full"
                                        placeholder="Optional"
                                    >
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8 text-gray-500 border-2 border-dashed border-gray-300 rounded-lg">
                            <p>Belum ada file. Klik "Tambah File" untuk menambahkan.</p>
                        </div>
                        @endif
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center space-x-2">
                        <button 
                            type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 disabled:opacity-50"
                            @disabled(count($files) === 0 && count($existingFiles ?? []) === 0)
                        >
                            {{ $bukti_id ? 'Update' : 'Upload Semua' }}
                        </button>

                        <a href="{{ route('bukti-pengeluarans.index') }}"
                           class="px-4 py-2 border rounded hover:bg-gray-50">
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>