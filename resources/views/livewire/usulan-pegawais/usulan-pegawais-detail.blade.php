<div>
  <h2 class="text-xl font-semibold mb-2">Detail Usulan Pegawai</h2>
  @if(isset($proposal) && $proposal)
    <div class="border rounded p-4 mb-4">
      <p class="mb-1"><strong>Nama Kegiatan:</strong> {{ $proposal->usulan->nama_kegiatan ?? '' }}</p>
      <p class="mb-1"><strong>Tanggal:</strong> {{ optional($proposal->usulan->tanggal_kegiatan)->format('d/m/Y') ?? '' }}</p>
      <p class="mb-1"><strong>Lokasi:</strong> {{ $proposal->usulan->lokasi_kegiatan ?? '' }}</p>
      <p class="mb-1"><strong>Pegawai:</strong> {{ $proposal->kepegawaian->nama ?? '' }}</p>
      <p class="mb-1"><strong>Status:</strong> {{ ucfirst($proposal->status) }}</p>
    </div>
    <div class="border rounded p-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
      <textarea wire:model.defer="reason" rows="4" class="border rounded w-full"></textarea>
      @error('reason')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
      <div class="mt-3">
        <button wire:click="submitReject" class="bg-red-600 text-white px-4 py-2 rounded">Tolak</button>
      </div>
    </div>
  @else
    <p>Tidak ada data usulan pegawai.</p>
  @endif
</div>
