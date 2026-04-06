<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    {{-- Header --}}
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Pengajuan Pencairan Dana</h2>
                        <a href="{{ route('keuangan.pengajuan-pencairans.create') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            + Pengajuan Baru
                        </a>
                    </div>

                    {{-- Flash Messages --}}
                    @if (session()->has('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Search & Filter --}}
                    <div class="flex gap-4 mb-4">
                        <input type="text"
                               wire:model.live="search"
                               placeholder="Cari nomor surat, pegawai, perencanaan..."
                               class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">

                        <select wire:model.live="filterStatus"
                                class="border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Disetujui</option>
                            <option value="rejected">Ditolak</option>
                            <option value="dicairkan">Dicairkan</option>
                        </select>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Surat</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pegawai</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Perencanaan</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($pengajuans as $index => $pengajuan)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm">{{ $pengajuans->firstItem() + $index }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $pengajuan->nomor_surat ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $pengajuan->pegawai->nama ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $pengajuan->perencanaan->nama_komponen ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $pengajuan->tanggal_pengajuan->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 text-sm font-semibold">Rp {{ number_format($pengajuan->total_nominal, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $pengajuan->status_badge }}">
                                                {{ $pengajuan->status_label }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm space-x-2">
                                            <a href="{{ route('keuangan.pengajuan-pencairans.show', $pengajuan->id) }}"
                                               class="text-blue-600 hover:text-blue-800">Detail</a>

                                            {{-- Dropdown Cetak Kwitansi --}}
                                            <div class="relative inline-block" x-data="{ open: false }">
                                                <button @click="open = !open" @click.away="open = false"
                                                        class="text-purple-600 hover:text-purple-800">
                                                    Cetak
                                                </button>
                                                <div x-show="open" class="absolute left-0 mt-2 w-48 bg-white border rounded-lg shadow-lg z-50">
                                                    <a href="{{ route('keuangan.pengajuan-pencairans.print', ['id' => $pengajuan->id, 'jenis' => 'dinas-luar-kota']) }}"
                                                       target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        Dinas Luar Kota
                                                    </a>
                                                    <a href="{{ route('keuangan.pengajuan-pencairans.print', ['id' => $pengajuan->id, 'jenis' => 'dinas-dalam-kota']) }}"
                                                       target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        Dinas Dalam Kota
                                                    </a>
                                                    <a href="{{ route('keuangan.pengajuan-pencairans.print', ['id' => $pengajuan->id, 'jenis' => 'dinas-dana-kantor']) }}"
                                                       target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        Dinas Dana Kantor
                                                    </a>
                                                    <a href="{{ route('keuangan.pengajuan-pencairans.print', ['id' => $pengajuan->id, 'jenis' => 'dinas-kegiatan']) }}"
                                                       target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        Dinas Kegiatan
                                                    </a>
                                                </div>
                                            </div>

                                            @if ($pengajuan->status === 'pending')
                                                <button wire:click="delete({{ $pengajuan->id }})"
                                                        wire:confirm="Yakin hapus pengajuan ini?"
                                                        class="text-red-600 hover:text-red-800">Hapus</button>
                                            @endif

                                            @if ($pengajuan->status === 'approved')
                                                <button wire:click="cairkan({{ $pengajuan->id }})"
                                                        wire:confirm="Tandai pengajuan ini sudah dicairkan?"
                                                        class="text-green-600 hover:text-green-800">Cairkan</button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                            Belum ada pengajuan pencairan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $pengajuans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>