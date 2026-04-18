<div>
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-bold border-b pb-2 mb-4">Lengkapi Detail Kegiatan</h3>
            
            <form wire:submit.prevent="save">
                <div class="mb-4">
                    <label class="block text-sm font-medium">Tujuan Kegiatan</label>
                    <input type="text" wire:model="tujuan_kegiatan" class="w-full border rounded p-2 focus:ring-blue-500">
                    @error('tujuan_kegiatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Waktu Pelaksanaan</label>
                    <input type="date" wire:model="waktu_kegiatan" class="w-full border rounded p-2">
                    @error('waktu_kegiatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Keterangan</label>
                    <textarea wire:model="keterangan" class="w-full border rounded p-2" rows="3"></textarea>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-200 rounded text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>