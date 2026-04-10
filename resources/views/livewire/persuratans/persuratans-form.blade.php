@php
use App\Models\Kepegawaian;
@endphp
<div>
    <h3 class="text-lg font-semibold mb-2">Persuratan Form</h3>
    <form wire:submit.prevent="submit" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Kepada</label>
            <select wire:model="pegawai_id" class="border rounded w-full p-2">
                <option value="">Pilih Pegawai</option>
                @foreach ($pegawais as $peg)
                    <option value="{{ $peg->id }}">{{ $peg->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Surat</label>
            <input type="text" class="border rounded w-full p-2" wire:model="nama_surat" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">File PDF</label>
            <input type="file" class="border rounded w-full p-2" wire:model="file_pdf" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Upload</label>
            <input type="date" class="border rounded w-full p-2" wire:model="tanggal_upload" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Perihal</label>
            <input type="text" class="border rounded w-full p-2" wire:model="perihal" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Jenis Anggaran</label>
            <select wire:model="jenis_anggaran" class="border rounded w-full p-2">
                <option value="BPMP">BPMP</option>
                <option value="LUAR BPMP">LUAR BPMP</option>
                <option value="GABUNGAN">GABUNGAN</option>
            </select>
        </div>
        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
        </div>
    </form>
</div>
