<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Laporan Kegiatan</h2>
                        <a href="{{ route('laporan-kegiatans.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Upload Laporan
                        </a>
                    </div>

                    <div class="flex gap-4 mb-4">
                        <input type="text" wire:model.live="search" placeholder="Cari judul laporan..." class="border rounded px-3 py-2 w-64">
                        <select wire:model.live="filterStatus" class="border rounded px-3 py-2">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    @if (session()->has('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pegawai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul Laporan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Perencanaan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($laporanKegiatans as $laporan)
                                <tr wire:key="{{ $laporan->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $laporan->kepegawaian->nama ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $laporan->judul_laporan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $laporan->perencanaan->nama_komponen ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $laporan->tanggal_mulai->format('d/m/Y') }} - {{ $laporan->tanggal_selesai->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($laporan->status === 'pending')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @elseif ($laporan->status === 'approved')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if ($laporan->status === 'pending')
                                            <button wire:click="approve({{ $laporan->id }})" wire:confirm="Terima laporan ini?" class="text-green-600 hover:text-green-900 mr-2">✅</button>
                                            <button wire:click="reject({{ $laporan->id }})" wire:confirm="Tolak laporan ini?" class="text-red-600 hover:text-red-900 mr-2">❌</button>
                                        @endif
                                        @if ($laporan->file_laporan)
                                            <a href="{{ asset('storage/' . $laporan->file_laporan) }}" target="_blank" class="text-blue-600 hover:text-blue-900 mr-2">📄</a>
                                        @endif
                                        <a href="{{ route('laporan-kegiatans.edit', $laporan->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">✏️</a>
                                        <button wire:click="delete({{ $laporan->id }})" wire:confirm="Yakin hapus laporan ini?" class="text-red-600 hover:text-red-900">🗑️</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data laporan kegiatan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $laporanKegiatans->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
