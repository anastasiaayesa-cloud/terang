<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Header & Tombol Tambah --}}
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Laporan Kegiatan</h2>
                        <a href="{{ route('laporan-kegiatans.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Upload Laporan
                        </a>
                    </div>

                    {{-- Fitur Pencarian --}}
                    <div class="flex gap-4 mb-4">
                        <input type="text" wire:model.live="search" placeholder="Cari nama pegawai..." class="border rounded px-3 py-2 w-64">
                    </div>

                    {{-- Notifikasi Sukses/Error --}}
                    @if (session()->has('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Tabel Laporan --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pegawai</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kegiatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Laporan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($approvedUsulanPegawai as $up)
                                    @php
                                        // Membuat kunci unik gabungan untuk mengecek status per individu
                                        $key = $up->usulan_id . '-' . $up->pegawai_id;
                                        $dataLaporan = $laporanMap[$key] ?? null;
                                    @endphp
                                    <tr wire:key="row-{{ $up->id }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $up->kepegawaian->nama ?? '-' }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $up->usulan?->nama_kegiatan ?? '-' }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $up->usulan?->tanggal_kegiatan ? \Carbon\Carbon::parse($up->usulan->tanggal_kegiatan)->format('d/m/Y') : '-' }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($dataLaporan)
                                                {{-- Status berdasarkan tabel laporan_kegiatans --}}
                                                @if($dataLaporan['status'] == 'approved')
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                        Approved
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Pending
                                                    </span>
                                                @endif
                                            @else
                                                {{-- Kondisi jika belum ada data di tabel laporan_kegiatans --}}
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Belum Upload
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-2">
                                                @if(!$dataLaporan)
                                                    {{-- Link Upload jika data belum ada --}}
                                                    <a href="{{ route('laporan-kegiatans.create', ['usulanId' => $up->usulan_id, 'pegawaiId' => $up->pegawai_id]) }}" 
                                                       class="text-blue-600 hover:text-blue-900">
                                                        Upload
                                                    </a>
                                                @else
                                                    {{-- Tombol ACC jika status masih pending --}}
                                                    @if($dataLaporan['status'] == 'pending')
                                                        <button 
                                                            wire:click="approve({{ $dataLaporan['id'] }})" 
                                                            class="text-green-600 hover:text-green-900 font-bold">
                                                            ACC
                                                        </button>
                                                        <span class="text-gray-300">|</span>
                                                    @endif

                                                    {{-- Tombol Download menggunakan kolom file_laporan --}}
                                                    @if(!empty($dataLaporan['file_laporan']))
                                                        <a href="{{ Storage::url($dataLaporan['file_laporan']) }}" 
                                                           target="_blank" 
                                                           class="text-indigo-600 hover:text-indigo-900">
                                                            Download
                                                        </a>
                                                        <span class="text-gray-300">|</span>
                                                    @endif

                                                    {{-- Tombol Hapus data laporan --}}
                                                    <button 
                                                        wire:click="delete({{ $dataLaporan['id'] }})" 
                                                        wire:confirm="Yakin ingin menghapus laporan ini?"
                                                        class="text-red-600 hover:text-red-900">
                                                        Hapus
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Tidak ada data usulan yang disetujui.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Navigasi Paginasi --}}
                    <div class="mt-4">
                        {{ $approvedUsulanPegawai->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>