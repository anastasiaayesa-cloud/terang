<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Pembayaran Keuangan</h2>
        <a href="{{ route('keuangans.index') }}" class="text-blue-600 hover:underline text-sm">← Kembali ke Daftar</a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 text-green-700 rounded border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Data Pegawai -->
        <div class="bg-white p-6 rounded-lg shadow border">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">📋 Data Pegawai</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Nama</span>
                    <span class="font-medium text-gray-900">{{ $pegawai->kepegawaian->nama ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">NIP</span>
                    <span class="font-medium text-gray-900">{{ $pegawai->kepegawaian->nip ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Jabatan</span>
                    <span class="font-medium text-gray-900">{{ $pegawai->kepegawaian->jabatan ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Pangkat</span>
                    <span class="font-medium text-gray-900">{{ $pegawai->kepegawaian->pangkat->nama ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Data Kegiatan -->
        <div class="bg-white p-6 rounded-lg shadow border">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">📋 Data Kegiatan</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Nama Kegiatan</span>
                    <span class="font-medium text-gray-900">{{ $usulan->nama_kegiatan ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tanggal</span>
                    <span class="font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($usulan->tanggal_kegiatanaan)->format('d M') }} - 
                        {{ \Carbon\Carbon::parse($usulan->sampai_tanggal)->format('d M Y') }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Lokasi</span>
                    <span class="font-medium text-gray-900">{{ $usulan->lokasi_kegiatan ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Bukti Pengeluaran -->
    <div class="mt-6 bg-white rounded-lg shadow border">
        <div class="p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-700">📂 Daftar Bukti Pengeluaran</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bukti</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Nominal Diminta</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Uang Dibayarkan</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($buktiPengeluarans as $bukti)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="text-sm font-medium text-gray-900">{{ $bukti->tipe_bukti_label }}</div>
                            <div class="text-xs text-gray-500">{{ $bukti->file_name }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $bukti->keterangan ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-right font-medium text-gray-900">
                            Rp {{ number_format($bukti->nominal, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-right">
                            @if($bukti->keuangan && $bukti->keuangan->uang_dibayarkan)
                                <span class="font-medium text-green-600">
                                    Rp {{ number_format($bukti->keuangan->uang_dibayarkan, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($bukti->keuangan && $bukti->keuangan->status == 'full')
                                <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Lunas</span>
                            @elseif($bukti->keuangan && $bukti->keuangan->status == 'sebagian')
                                <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">Sebagian</span>
                            @else
                                <span class="inline-block px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">Pending</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($bukti->keuangan && in_array($bukti->keuangan->status, ['full', 'sebagian']))
                                <span class="text-green-600 text-sm">✓ Lunas</span>
                            @else
                                <div class="flex justify-center gap-2">
                                    <button 
                                        wire:click="bayarfull({{ $bukti->id }})"
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs"
                                    >
                                        Bayar Full
                                    </button>
                                    <button 
                                        wire:click="openModal({{ $bukti->id }})"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs"
                                    >
                                        Sebagian
                                    </button>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            Belum ada bukti pengeluaran yang diupload
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="2" class="px-4 py-3 text-right font-bold text-gray-700">Total:</td>
                        <td class="px-4 py-3 text-right font-bold text-gray-900">
                            Rp {{ number_format($buktiPengeluarans->sum('nominal'), 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-green-600">
                            Rp {{ number_format($buktiPengeluarans->sum(function($b) { return $b->keuangan ? $b->keuangan->uang_dibayarkan : 0; }), 0, ',', '.') }}
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Modal Bayar Sebagian -->
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Bayar Sebagian</h3>
            <form wire:submit.prevent="bayarsebagian">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nominal yang Dibayarkan</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 text-sm">Rp</span>
                        <input 
                            type="number" 
                            wire:model="nominalBayar"
                            class="block w-full pl-8 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="0"
                        >
                    </div>
                    @error('nominalBayar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Pembayaran Sebagian</label>
                    <textarea 
                        wire:model="alasan"
                        rows="3"
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Jelaskan mengapa nominal dibayarkan tidak sesuai..."
                    ></textarea>
                    @error('alasan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end gap-3">
                    <button 
                        type="button"
                        wire:click="closeModal"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                    >
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>