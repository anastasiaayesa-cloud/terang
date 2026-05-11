<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Preview Pembayaran</h2>
        <a href="{{ route('keuangans.bayar', ['usulan_id' => $usulan_id, 'pegawai_id' => $pegawai_id]) }}" class="text-blue-600 hover:underline text-sm">← Kembali ke Pembayaran</a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 text-green-700 rounded border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-50 text-red-700 rounded border border-red-200">
            {{ session('error') }}
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

    <!-- Tabel Editable -->
    <div class="mt-6 bg-white rounded-lg shadow border">
        <div class="p-4 border-b flex justify-between items-center">
            <div class="flex items-center gap-4">
                <h3 class="text-lg font-semibold text-gray-700">📂 Edit Pembayaran</h3>
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">Tanggal Kwitansi:</label>
                    <input 
                        type="date" 
                        wire:model="tanggal_kwitansi"
                        wire:change="simpanTanggalKwitansi"
                        class="border-gray-300 rounded-lg text-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                    @error('tanggal_kwitansi')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="flex gap-2">
                <a 
                    href="{{ route('keuangans.preview.cetak', ['usulan_id' => $usulan_id, 'pegawai_id' => $pegawai_id]) }}"
                    target="_blank"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm inline-flex items-center"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Kwitansi
                </a>
                <button 
                    wire:click="saveAll"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm inline-flex items-center"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Semua
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Perincian Bayar</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Nominal</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Uang Dibayarkan</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($payments as $index => $payment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-center text-sm text-gray-500">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-4 py-3">
                            <input 
                                type="text" 
                                wire:model="payments.{{ $index }}.perincian_bayar"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"
                                placeholder="Masukkan perincian"
                            >
                            @error("payments.{$index}.perincian_bayar")
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </td>
                        <td class="px-4 py-3">
                            <select 
                                wire:model="payments.{{ $index }}.jenis_pembayaran"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"
                            >
                                @foreach($jenisOptions as $jenis)
                                    <option value="{{ $jenis }}">{{ $jenis }}</option>
                                @endforeach
                            </select>
                            @error("payments.{$index}.jenis_pembayaran")
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
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
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            Belum ada pembayaran
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-700">Total:</td>
                        <td class="px-4 py-3 text-right font-bold text-gray-900">
                            Rp {{ number_format(collect($payments)->sum('nominal'), 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center font-bold text-gray-900">
                            {{ collect($payments)->sum('jumlah') }}
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-green-600">
                            Rp {{ number_format(collect($payments)->sum('uang_dibayarkan'), 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>