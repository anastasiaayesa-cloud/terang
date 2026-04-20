<div class="max-w-5xl mx-auto py-8 px-4">
    <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-200">
        
        {{-- Header --}}
        <div class="bg-gray-800 px-6 py-4 flex justify-between items-center">
            <div>
                <h3 class="text-white font-bold text-lg">Input Dokumen Persuratan</h3>
                <p class="text-gray-400 text-xs mt-0.5">Dokumen akan otomatis terhubung ke setiap pegawai yang disetujui.</p>
            </div>
            <button type="button" wire:click="addInput({{ $i }})" 
                    class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold py-2 px-4 rounded-lg shadow transition flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                TAMBAH BARIS SURAT
            </button>
        </div>

        <div class="p-6">
            {{-- Info Perencanaan --}}
            <div class="mb-6 p-4 bg-indigo-50 border border-indigo-100 rounded-lg shadow-sm">
                <label class="block text-[10px] font-black text-indigo-900 uppercase tracking-widest mb-2">
                    Detail Perencanaan:
                </label>
                <div class="text-sm grid grid-cols-1 md:grid-cols-2 gap-2 text-gray-800">
                    <div><span class="text-gray-500 font-medium">Kode:</span> {{ $perencanaan->kode ?? '-' }}</div>
                    <div><span class="text-gray-500 font-medium">Komponen:</span> {{ $perencanaan->nama_komponen }}</div>
                    <div class="md:col-span-2">
                        <span class="text-gray-500 font-medium">Usulan:</span> 
                        @if($perencanaan->usulan)
                            <span class="text-indigo-700 font-semibold">{{ $perencanaan->usulan->nama_kegiatan }}</span>
                            <span class="text-[10px] bg-indigo-200 text-indigo-800 px-2 py-0.5 rounded ml-2">ID: {{ $perencanaan->usulan_id }}</span>
                        @else
                            <span class="text-orange-600 font-medium italic">Perencanaan Manual</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Bagian Penerima Surat --}}
            <div class="mb-8 p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">
                    Penerima Surat (Pegawai dari Tabel Usulan):
                </label>
                
                {{-- PERBAIKAN: Menggunakan variabel $usulan_pegawais_approved sesuai file PHP --}}
                @if(isset($usulan_pegawais_approved) && count($usulan_pegawais_approved) > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($usulan_pegawais_approved as $item)
                            <div class="flex items-center text-[11px] font-semibold text-green-700 bg-green-50 px-3 py-1.5 rounded-full border border-green-100 uppercase">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></span>
                                {{-- PERBAIKAN: Akses array kepegawaian --}}
                                {{ $item['kepegawaian']['nama'] ?? 'Tanpa Nama' }}
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex items-center text-sm text-red-600 bg-red-50 p-4 rounded-lg border border-red-100">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        <div>
                            <p class="font-bold">Penerima Tidak Ditemukan!</p>
                            <p class="text-xs">Tidak ada pegawai dengan status 'approved' pada Usulan ID: {{ $perencanaan->usulan_id ?? 'N/A' }}</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Form Loop --}}
            <form wire:submit.prevent="submit" class="space-y-6">
                @foreach($inputs as $key => $value)
                    <div class="relative p-5 border border-gray-200 rounded-xl bg-gray-50/50 hover:bg-white hover:shadow-md transition-all duration-200">
                        
                        <div class="flex justify-between items-center mb-5">
                            <div class="flex items-center space-x-2">
                                <span class="bg-gray-800 text-white text-[10px] font-bold px-3 py-1 rounded-md">DOKUMEN #{{ $loop->iteration }}</span>
                            </div>
                            @if(count($inputs) > 1)
                                <button type="button" wire:click="removeInput({{ $key }})" class="text-gray-400 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Nama / Nomor Surat</label>
                                <input type="text" wire:model="inputs.{{ $key }}.nama_surat" class="w-full border-gray-300 rounded-lg text-sm p-2.5 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                @error('inputs.'.$key.'.nama_surat') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Kategori</label>
                                <select wire:model="inputs.{{ $key }}.persuratan_kategori_id" class="w-full border-gray-300 rounded-lg text-sm p-2.5 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                    <option value="">-- Pilih --</option>
                                    @foreach($kategoris as $kat)
                                        <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                @error('inputs.'.$key.'.persuratan_kategori_id') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Anggaran</label>
                                <select wire:model="inputs.{{ $key }}.jenis_anggaran" class="w-full border-gray-300 rounded-lg text-sm p-2.5 shadow-sm">
                                    <option value="BPMP">BPMP</option>
                                    <option value="LUAR BPMP">LUAR BPMP</option>
                                    <option value="GABUNGAN">GABUNGAN</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Tanggal Terbit</label>
                                <input type="date" wire:model="inputs.{{ $key }}.tanggal_upload" class="w-full border-gray-300 rounded-lg text-sm p-2.5 shadow-sm">
                                @error('inputs.'.$key.'.tanggal_upload') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-3">
                                <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Unggah PDF</label>
                                <input type="file" wire:model="inputs.{{ $key }}.file_pdf" class="w-full text-xs text-gray-500 border border-gray-300 rounded-lg p-1.5 bg-white shadow-sm">
                                @error('inputs.'.$key.'.file_pdf') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-4">
                                <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Perihal</label>
                                <textarea wire:model="inputs.{{ $key }}.perihal" rows="2" class="w-full border-gray-300 rounded-lg text-sm p-2.5 shadow-sm"></textarea>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                    <a href="{{ route('persuratans.index') }}" class="text-sm text-gray-500 hover:text-gray-800 font-medium transition">Batal</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-10 rounded-xl shadow-lg transition-all flex items-center">
                        <span wire:loading wire:target="submit" class="animate-spin mr-2">...</span>
                        SIMPAN DATA PERSURATAN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>