<div>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    {{-- Header --}}
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Detail Pengajuan</h2>
                        <a href="{{ route('keuangan.pengajuan-pencairans.index') }}"
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                            Kembali
                        </a>
                    </div>

                    {{-- Flash Messages --}}
                    @if (session()->has('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Info Pengajuan --}}
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <span class="text-gray-600 text-sm">No. Surat</span>
                            <p class="font-semibold">{{ $pengajuan->nomor_surat ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm">Status</span>
                            <p>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $pengajuan->status_badge }}">
                                    {{ $pengajuan->status_label }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm">Pegawai</span>
                            <p class="font-semibold">{{ $pengajuan->pegawai->nama ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm">Perencanaan</span>
                            <p class="font-semibold">{{ $pengajuan->perencanaan->nama_komponen ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm">Tanggal Pengajuan</span>
                            <p class="font-semibold">{{ $pengajuan->tanggal_pengajuan->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm">Tanggal Cair</span>
                            <p class="font-semibold">{{ $pengajuan->tanggal_cair?->format('d/m/Y') ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- Bukti Pengeluaran --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Bukti Pengeluaran</h3>
                        <div class="border rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Tipe</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Nominal</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Tanggal</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($pengajuan->buktiPengeluarans as $bukti)
                                        <tr>
                                            <td class="px-4 py-2 text-sm">{{ $bukti->tipe_bukti_label }}</td>
                                            <td class="px-4 py-2 text-sm">Rp {{ number_format($bukti->nominal, 0, ',', '.') }}</td>
                                            <td class="px-4 py-2 text-sm">{{ $bukti->tanggal_bukti?->format('d/m/Y') ?? '-' }}</td>
                                            <td class="px-4 py-2 text-sm">{{ $bukti->keterangan ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-4 text-center text-gray-500">Tidak ada bukti pengeluaran.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Ringkasan Nominal --}}
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Total Bukti:</span>
                            <span class="font-semibold">Rp {{ number_format($pengajuan->total_nominal - $pengajuan->uang_harian_total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Uang Harian ({{ $pengajuan->uang_harian_nominal }} x {{ $pengajuan->jumlah_hari }} hari):</span>
                            <span class="font-semibold">Rp {{ number_format($pengajuan->uang_harian_total, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t pt-2 flex justify-between text-lg">
                            <span class="font-bold">Total Pengajuan:</span>
                            <span class="font-bold text-blue-600">Rp {{ number_format($pengajuan->total_nominal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Catatan Reviewer --}}
                    @if ($pengajuan->catatan_reviewer)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <span class="text-sm font-medium text-yellow-800">Catatan Reviewer:</span>
                            <p class="text-yellow-700 mt-1">{{ $pengajuan->catatan_reviewer }}</p>
                        </div>
                    @endif

                    {{-- Actions --}}
                    @if ($pengajuan->status === 'pending')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                            <textarea wire:model="catatanReviewer"
                                      rows="3"
                                      class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                                      placeholder="Tambahkan catatan..."></textarea>
                        </div>
                        <div class="flex gap-4">
                            <button wire:click="approve"
                                    wire:confirm="Setujui pengajuan ini?"
                                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                                Setujui
                            </button>
                            <button wire:click="reject"
                                    wire:confirm="Tolak pengajuan ini?"
                                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg">
                                Tolak
                            </button>
                        </div>
                    @endif

                    @if ($pengajuan->status === 'approved')
                        <button wire:click="cairkan"
                                wire:confirm="Tandai pengajuan ini sudah dicairkan?"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Tandai Sudah Dicairkan
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>