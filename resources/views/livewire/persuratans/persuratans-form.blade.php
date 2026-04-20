<div class="max-w-5xl mx-auto py-8 px-4">
    <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-200">
        
        {{-- Header --}}
        <div class="bg-gray-800 px-6 py-4 flex justify-between items-center">
            <div>
                <h3 class="text-white font-bold text-lg">Input Dokumen Persuratan</h3>
                <p class="text-gray-400 text-xs mt-0.5">Anda dapat menambahkan lebih dari satu surat sekaligus.</p>
            </div>
            <button type="button" wire:click="addInput({{ $i }})" 
                    class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold py-2 px-4 rounded-lg shadow transition flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                TAMBAH BARIS SURAT
            </button>
        </div>

        <div class="p-6">
            {{-- Info Perencanaan --}}
            <div class="mb-6 p-4 bg-indigo-50 border border-indigo-100 rounded-lg">
                <label class="block text-[10px] font-black text-indigo-900 uppercase tracking-widest mb-2">
                    Perencanaan:
                </label>
                <div class="text-sm font-medium text-gray-800">
                    <p><span class="text-gray-500">Kode:</span> {{ $perencanaan->kode ?? '-' }}</p>
                    <p><span class="text-gray-500">Komponen:</span> {{ $perencanaan->nama_komponen }}</p>
                    @if($perencanaan->usulan)
                        <p><span class="text-gray-500">Usulan:</span> {{ $perencanaan->usulan->nama_kegiatan }}</p>
                    @else
                        <p><span class="text-gray-500">Usulan:</span> <span class="text-orange-600 font-medium">Perencanaan Manual (Tanpa Usulan)</span></p>
                    @endif
                </div>
            </div>

            {{-- Info Pegawai yang Di-ACC ( hanya jika dari Usulan ) --}}
            @if($perencanaan->usulan_id && count($usulan_pegawais_approved) > 0)
                <div class="mb-8 p-4 bg-indigo-50 border border-indigo-100 rounded-lg">
                    <label class="block text-[10px] font-black text-indigo-900 uppercase tracking-widest mb-3 text-center md:text-left">
                        Penerima Surat (Pegawai Approved):
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($usulan_pegawais_approved as $up)
                            <div class="flex items-center text-xs font-medium text-gray-700 bg-white p-2.5 rounded-md border border-indigo-200 shadow-sm">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                {{ $up['kepegawaian']['nama'] ?? 'Unknown' }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif($perencanaan->usulan_id && count($usulan_pegawais_approved) == 0)
                <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center text-sm text-red-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span class="font-medium">Belum ada pegawai yang di-approve untuk perencanaan ini.</span>
                    </div>
                    <p class="text-xs text-red-600 mt-2">Silakan lakukan approval di halaman Usulan Pegawai terlebih dahulu.</p>
                </div>
            @else
                <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center text-sm text-blue-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">Perencanaan Manual - Tidak memerlukan approval Usulan Pegawai</span>
                    </div>
                </div>
            @endif

            {{-- Form --}}
            @if(!$perencanaan->usulan_id || count($usulan_pegawais_approved) > 0)
                <form wire:submit.prevent="submit" class="space-y-6">
                    @foreach($inputs as $key => $value)
                        <div class="relative p-5 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50 hover:border-indigo-300 transition group">
                            
                            {{-- Badge Nomor & Tombol Hapus --}}
                            <div class="flex justify-between items-center mb-4">
                                <span class="bg-indigo-600 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase">Dokumen #{{ $loop->iteration }}</span>
                                @if(count($inputs) > 1)
                                    <button type="button" wire:click="removeInput({{ $key }})" 
                                            class="text-red-500 hover:text-red-700 text-xs font-bold flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        HAPUS BARIS
                                    </button>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                {{-- Nama Surat --}}
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Nama / Nomor Surat</label>
                                    <input type="text" wire:model="inputs.{{ $key }}.nama_surat" 
                                           class="w-full border-gray-300 rounded-lg text-sm p-2.5 focus:ring-indigo-500 focus:border-indigo-500" placeholder="ST/001/...">
                                    @error('inputs.'.$key.'.nama_surat') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>

                                {{-- Jenis Anggaran --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Anggaran</label>
                                    <select wire:model="inputs.{{ $key }}.jenis_anggaran" class="w-full border-gray-300 rounded-lg text-sm p-2.5">
                                        <option value="BPMP">BPMP</option>
                                        <option value="LUAR BPMP">LUAR BPMP</option>
                                        <option value="GABUNGAN">GABUNGAN</option>
                                    </select>
                                </div>

                                {{-- Tanggal --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Tanggal Terbit</label>
                                    <input type="date" wire:model="inputs.{{ $key }}.tanggal_upload" class="w-full border-gray-300 rounded-lg text-sm p-2.5">
                                </div>

                                {{-- File --}}
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-600 mb-1">File PDF</label>
                                    <input type="file" wire:model="inputs.{{ $key }}.file_pdf" 
                                           class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-300 rounded-lg p-1.5 bg-white">
                                    @error('inputs.'.$key.'.file_pdf') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>

                                {{-- Perihal --}}
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Perihal / Keterangan</label>
                                    <textarea wire:model="inputs.{{ $key }}.perihal" rows="2" 
                                              class="w-full border-gray-300 rounded-lg text-sm p-2.5" placeholder="Isi ringkasan surat..."></textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Submit Button --}}
                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('persuratans.index') }}" class="text-sm text-gray-500 hover:text-gray-800 font-medium transition">Kembali</a>
                        <button type="submit" 
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-8 rounded-lg shadow-lg transition-all flex items-center">
                            <svg wire:loading wire:target="submit" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            SIMPAN SEMUA DOKUMEN
                        </button>
                    </div>
                </form>
            @else
                <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                    <a href="{{ route('persuratans.index') }}" class="text-sm text-gray-500 hover:text-gray-800 font-medium transition">Kembali</a>
                </div>
            @endif
        </div>
    </div>
</div>