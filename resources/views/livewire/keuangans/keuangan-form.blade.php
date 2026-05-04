<div class="p-6 bg-white border-b border-gray-200 shadow-sm sm:rounded-lg">
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Pilih Kegiatan (Usulan)</label>
                <select wire:model.live="usulan_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Pilih Kegiatan --</option>
                    @foreach($usulans as $u)
                        <option value="{{ $u->id }}">{{ $u->nama_kegiatan }}</option>
                    @endforeach
                </select>
                @error('usulan_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Pegawai (Hanya yang Approved)</label>
                <select wire:model="pegawai_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" {{ empty($pegawaiTersedia) ? 'disabled' : '' }}>
                    <option value="">-- Pilih Pegawai --</option>
                    @foreach($pegawaiTersedia as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
                @if(empty($pegawaiTersedia) && $usulan_id)
                    <p class="text-amber-500 text-xs mt-1 italic font-semibold">* Belum ada pegawai yang di-approve untuk kegiatan ini.</p>
                @endif
                @error('pegawai_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Perincian Pembayaran</label>
                <input type="text" wire:model="perincian_bayar" placeholder="Contoh: Transport Lokal Jakarta" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @error('perincian_bayar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nominal Satuan (Rp)</label>
                <input type="number" wire:model.live="nominal" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Jumlah (Kali/Hari)</label>
                <input type="number" wire:model.live="jumlah" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Jenis Kategori</label>
                <select wire:model="jenis" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="BIAYA PERJALANAN DINAS">BIAYA PERJALANAN DINAS</option>
                    <option value="PENGELUARAN RIIL">PENGELUARAN RIIL</option>
                    <option value="KEDUANYA">KEDUANYA</option>
                </select>
            </div>

            <div class="bg-gray-50 p-4 rounded-md border border-dashed border-gray-300">
                <span class="text-sm text-gray-500 italic uppercase">Estimasi Total Bayar:</span>
                <div class="text-2xl font-bold text-indigo-600">
                    Rp {{ number_format($total_preview, 0, ',', '.') }}
                </div>
            </div>

        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow">
                Simpan Keuangan
            </button>
        </div>
    </form>
</div>