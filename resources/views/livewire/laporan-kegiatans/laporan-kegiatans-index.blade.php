<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Header & Tombol Tambah --}}
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold">Laporan Kegiatan</h2>
                            @if(!$isSuperAdmin)
                                @if($hasKepegawaian)
                                    <p class="text-sm text-gray-500 mt-1">Menampilkan kegiatan yang Anda ikuti</p>
                                @else
                                    <p class="text-sm text-gray-500 mt-1">Anda tidak memiliki data kepegawaian</p>
                                @endif
                            @endif
                        </div>
                        <a href="{{ route('laporan-kegiatans.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Upload Laporan
                        </a>
                    </div>

                    {{-- Fitur Pencarian --}}
                    <div class="flex gap-4 mb-4">
                        <input type="text" wire:model.live="search" placeholder="Cari nama kegiatan..." class="border rounded px-3 py-2 w-64">
                    </div>

                    {{-- Pesan Khusus untuk User Tanpa Kepegawaian --}}
                    @if(!$isSuperAdmin && !$hasKepegawaian)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-yellow-800">Anda belum terdaftar sebagai Pegawai</p>
                                    <p class="text-sm text-yellow-600 mt-1">Silakan hubungi administrator untuk menambahkan data kepegawaian Anda.</p>
                                </div>
                            </div>
                        </div>
                    @endif

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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kegiatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pegawai</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Laporan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($activities as $activity)
                                    @php
                                        $peserta = $activity->usulanPegawais->where('status', 'approved');
                                    @endphp
                                    @foreach($peserta as $index => $up)
                                        @php
                                            $key = $activity->id . '-' . $up->pegawai_id;
                                            $dataLaporan = $laporanMap[$key] ?? null;
                                        @endphp
                                        <tr wire:key="row-{{ $activity->id }}-{{ $up->id }}">
                                            @if($index == 0)
                                                <td class="px-6 py-4 text-sm text-gray-900 font-medium" rowspan="{{ $peserta->count() }}">
                                                    {{ $activity->nama_kegiatan }}
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        {{ $activity->lokasi_kegiatan }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" rowspan="{{ $peserta->count() }}">
                                                    {{ $activity->tanggal_kegiatan ? \Carbon\Carbon::parse($activity->tanggal_kegiatan)->format('d/m/Y') : '-' }}
                                                    @if($activity->sampai_tanggal)
                                                        <span class="text-gray-400"> - {{ \Carbon\Carbon::parse($activity->sampai_tanggal)->format('d/m/Y') }}</span>
                                                    @endif
                                                </td>
                                            @endif

                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $up->kepegawaian->nama ?? '-' }}
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if($dataLaporan)
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
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        Belum Upload
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex gap-2">
                                                    @if(!$dataLaporan)
                                                        <a href="{{ route('laporan-kegiatans.create', ['usulanId' => $activity->id, 'pegawaiId' => $up->pegawai_id]) }}" 
                                                           class="text-blue-600 hover:text-blue-900">
                                                            Upload
                                                        </a>
                                                    @else
                                                        @if($isSuperAdmin && $dataLaporan['status'] == 'pending')
                                                            <button 
                                                                wire:click="approve({{ $dataLaporan['id'] }})" 
                                                                class="text-green-600 hover:text-green-900 font-bold">
                                                                ACC
                                                            </button>
                                                            <span class="text-gray-300">|</span>
                                                        @endif

                                                        @if(!empty($dataLaporan['file_laporan']))
                                                            <a href="{{ Storage::url($dataLaporan['file_laporan']) }}" 
                                                               target="_blank" 
                                                               class="text-indigo-600 hover:text-indigo-900">
                                                                Download
                                                            </a>
                                                            @if($isSuperAdmin)
                                                                <span class="text-gray-300">|</span>
                                                            @endif
                                                        @endif

                                                        @if($isSuperAdmin)
                                                            <button 
                                                                wire:click="delete({{ $dataLaporan['id'] }})" 
                                                                wire:confirm="Yakin ingin menghapus laporan ini?"
                                                                class="text-red-600 hover:text-red-900">
                                                                Hapus
                                                            </button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Tidak ada data kegiatan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Navigasi Paginasi --}}
                    <div class="mt-4">
                        {{ $activities->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>