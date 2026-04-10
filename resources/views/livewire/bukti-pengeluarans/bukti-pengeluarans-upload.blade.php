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

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form wire:submit.prevent="submit" x-data="fileUploadManager()">
                    
                    <!-- Pilih Perencanaan -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Perencanaan <span class="text-red-500">*</span>
                        </label>
                        <select 
                            wire:model="perencanaan_id"
                            class="border rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="{{ $bukti_id ? 'true' : 'false' }}"
                        >
                            <option value="">-- Pilih Perencanaan --</option>
                            @foreach ($perencanaanList as $p)
                                <option value="{{ $p->id }}" {{ $bukti_id && $perencanaan_id == $p->id ? 'selected' : '' }}>
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

                    <!-- Drag & Drop Zone -->
                    <div class="mb-6">
                        <div 
                            x-data
                            @dragover.prevent="$el.classList.add('border-blue-500', 'bg-blue-50')"
                            @dragleave.prevent="$el.classList.remove('border-blue-500', 'bg-blue-50')"
                            @drop.prevent="handleDrop($event); $el.classList.remove('border-blue-500', 'bg-blue-50')"
                            @click="$refs.fileInput.click()"
                            class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-400 hover:bg-gray-50 transition"
                        >
                            <input 
                                type="file" 
                                x-ref="fileInput" 
                                multiple 
                                accept=".pdf,.jpg,.jpeg,.png"
                                class="hidden"
                                @change="handleFiles($event.target.files)"
                            >
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-600">
                                <span class="font-medium text-blue-600">Klik untuk browse</span> atau drag & drop files
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                PDF, JPG, PNG (Max 5MB per file)
                            </p>
                        </div>
                    </div>

                    <!-- Existing Files (Edit Mode) -->
                    @if($existingFiles && count($existingFiles) > 0)
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">
                            File Existing (Centang untuk Pertahankan)
                        </h4>
                        <div class="space-y-3">
                            @foreach($existingFiles as $bukti)
                            <div class="flex items-start space-x-3 p-3 border rounded-lg bg-gray-50">
                                <input 
                                    type="checkbox" 
                                    wire:model="keepFiles.{{ $bukti->id }}"
                                    @change="$wire.toggleKeepFile({{ $bukti->id }})"
                                    class="mt-1 h-4 w-4 text-blue-600 rounded"
                                    checked
                                >
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-lg">{{ $bukti->tipe_bukti_label_icon }}</span>
                                            <span class="text-sm font-medium">{{ $bukti->file_name }}</span>
                                            <span class="text-xs text-gray-500">({{ number_format($bukti->file_size / 1024 / 1024, 2) }} MB)</span>
                                        </div>
                                        <button 
                                            type="button"
                                            @click="$wire.removeExistingFile({{ $bukti->id }})"
                                            class="text-red-600 hover:text-red-800 text-sm"
                                        >
                                            Hapus
                                        </button>
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
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Nominal *</label>
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
                    <template x-if="files.length > 0">
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">
                                File Baru (<span x-text="files.length"></span>)
                            </h4>
                            <div class="space-y-3">
                                <template x-for="(file, index) in files" :key="file.id">
                                    <div class="p-3 border rounded-lg bg-blue-50">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-lg" x-text="getFileIcon(file)"></span>
                                                <span class="text-sm font-medium" x-text="file.name"></span>
                                                <span class="text-xs text-gray-500" x-text="`(${formatFileSize(file.size)})`"></span>
                                            </div>
                                            <button 
                                                type="button"
                                                @click="removeFile(index)"
                                                class="text-red-600 hover:text-red-800 text-sm"
                                            >
                                                Hapus
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Tipe Bukti *</label>
                                                <select 
                                                    x-model="file.tipe_bukti"
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
                                                    x-model="file.nominal"
                                                    @input="updateTotal()"
                                                    class="border rounded px-2 py-1 text-sm w-full"
                                                    placeholder="0"
                                                    min="0"
                                                >
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label>
                                                <input 
                                                    type="date" 
                                                    x-model="file.tanggal_bukti"
                                                    class="border rounded px-2 py-1 text-sm w-full"
                                                >
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Keterangan</label>
                                                <input 
                                                    type="text" 
                                                    x-model="file.keterangan"
                                                    class="border rounded px-2 py-1 text-sm w-full"
                                                    placeholder="Optional"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Total Nominal -->
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-green-800">💰 TOTAL NOMINAL:</span>
                            <span class="text-lg font-bold text-green-800" x-text="formatCurrency(totalNominal)"></span>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center space-x-2">
                        <button 
                            type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 disabled:opacity-50"
                            :disabled="files.length === 0 && {{ count($existingFiles ?? []) }} === 0"
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

@push('scripts')
<script>
function fileUploadManager() {
    return {
        files: [],
        totalNominal: 0,
        nextId: 1,

        handleDrop(event) {
            const droppedFiles = event.dataTransfer.files;
            this.handleFiles(droppedFiles);
        },

        handleFiles(fileList) {
            for (let file of fileList) {
                // Validasi ukuran file (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert(`File ${file.name} terlalu besar (max 5MB)`);
                    continue;
                }

                // Validasi tipe file
                const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    alert(`File ${file.name} tidak didukung (hanya PDF, JPG, PNG)`);
                    continue;
                }

                this.files.push({
                    id: this.nextId++,
                    file: file,
                    name: file.name,
                    size: file.size,
                    type: file.type,
                    tipe_bukti: '',
                    nominal: '',
                    keterangan: '',
                    tanggal_bukti: new Date().toISOString().split('T')[0],
                });
            }

            this.updateTotal();
            this.syncToLivewire();
        },

        removeFile(index) {
            this.files.splice(index, 1);
            this.updateTotal();
            this.syncToLivewire();
        },

        updateTotal() {
            this.totalNominal = this.files.reduce((sum, file) => {
                return sum + (parseFloat(file.nominal) || 0);
            }, 0);
        },

        formatCurrency(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        },

        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        getFileIcon(file) {
            if (file.type.includes('pdf')) return '📄';
            if (file.type.includes('image')) return '🖼️';
            return '📎';
        },

        syncToLivewire() {
            // Sync files ke Livewire
            this.$wire.files = this.files.map(f => ({
                file: f.file,
                tipe_bukti: f.tipe_bukti,
                nominal: f.nominal,
                keterangan: f.keterangan,
                tanggal_bukti: f.tanggal_bukti,
            }));
        }
    }
}
</script>
@endpush
