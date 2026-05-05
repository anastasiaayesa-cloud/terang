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

    <!-- Daftar Pembayaran (Bukti + Manual) -->
    <div class="mt-6 bg-white rounded-lg shadow border">
        <div class="p-4 border-b flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-700">📂 Daftar Pembayaran</h3>
            <a 
                href="{{ route('keuangans.preview', ['usulan_id' => $usulan_id, 'pegawai_id' => $pegawai_id]) }}"
                class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm inline-flex items-center"
            >
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Preview
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Nominal</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Uang Dibayarkan</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($allPayments as $payment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            @if($payment['jenis'] == 'bukti')
                                <div class="text-sm font-medium text-gray-900">{{ $payment['keterangan'] ?? '-' }}</div>
                                <div class="text-xs text-gray-500">Bukti Pengeluaran</div>
                            @else
                                <div class="text-sm font-medium text-gray-900">{{ $payment['keterangan'] }}</div>
                                <div class="text-xs text-yellow-600 font-medium">Pembayaran Manual</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-right font-medium text-gray-900">
                            Rp {{ number_format($payment['nominal'] ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-center text-gray-900">
                            {{ $payment['jumlah'] }}
                        </td>
                        <td class="px-4 py-3 text-sm text-right">
                            @if($payment['uang_dibayarkan'])
                                <span class="font-medium text-green-600">
                                    Rp {{ number_format($payment['uang_dibayarkan'], 0, ',', '.') }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($payment['status'] == 'full')
                                <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Lunas</span>
                            @elseif($payment['status'] == 'sebagian')
                                <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">Sebagian</span>
                            @else
                                <span class="inline-block px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">Pending</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($payment['jenis'] == 'manual')
                                <span class="text-green-600 text-sm">✓ Lunas</span>
                            @else
                                @if(in_array($payment['status'], ['full', 'sebagian']))
                                    <span class="text-green-600 text-sm">✓ Lunas</span>
                                @else
                                    <div class="flex justify-center gap-2">
                                        <button 
                                            wire:click="bayarfull({{ $payment['id'] }})"
                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs"
                                        >
                                            Bayar Full
                                        </button>
                                        <button 
                                            wire:click="openModal({{ $payment['id'] }})"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs"
                                        >
                                            Sebagian
                                        </button>
                                    </div>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            Belum ada pembayaran
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td class="px-4 py-3 text-right font-bold text-gray-700">Total:</td>
                        <td class="px-4 py-3 text-right font-bold text-gray-900">
                            Rp {{ number_format(collect($allPayments)->sum('nominal'), 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center font-bold text-gray-900">
                            {{ collect($allPayments)->sum('jumlah') }}
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-green-600">
                            Rp {{ number_format(collect($allPayments)->sum('uang_dibayarkan'), 0, ',', '.') }}
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Tombol Tambah Pembayaran Manual -->
    <div class="mt-4">
        <button 
            wire:click="openModalManual"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm inline-flex items-center"
        >
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Pembayaran
        </button>
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

    <!-- Modal Tambah Pembayaran Manual -->
    @if($showModalManual)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Tambah Pembayaran</h3>
            <form wire:submit.prevent="saveManual">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Pembayaran</label>
                    <input 
                        type="text"
                        wire:model="manualPerincian"
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Contoh: Honorarium Pelatihan"
                    >
                    @error('manualPerincian') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nominal (per unit)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 text-sm">Rp</span>
                        <input 
                            type="number"
                            wire:model="manualNominal"
                            class="block w-full pl-8 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="0"
                        >
                    </div>
                    @error('manualNominal') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                    <input 
                        type="number"
                        wire:model="manualJumlah"
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="1"
                        min="1"
                    >
                    @error('manualJumlah') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4 bg-gray-50 p-3 rounded">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Total:</span>
                        <span class="text-lg font-bold text-green-600">
                            Rp {{ number_format(floatval($manualNominal ?? 0) * intval($manualJumlah ?? 1), 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button 
                        type="button"
                        wire:click="closeModalManual"
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