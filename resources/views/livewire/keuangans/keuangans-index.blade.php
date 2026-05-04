<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Pengajuan Pencairan Dana</h2>
        <a href="{{ route('keuangans.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
            + Tambah Pengajuan
        </a>
    </div>

    <div class="mb-4">
        <input wire:model.live="search" type="text" placeholder="Cari nama kegiatan..." class="w-full md:w-1/3 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tempat</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($usulans as $usulan)
                <tr>
                    <td class="px-6 py-4">
                        <div class="text-sm font-semibold text-gray-900">{{ $usulan->nama_kegiatan }}</div>
                        <div class="text-xs text-gray-500">ID: #{{ $usulan->id }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-700">
                            {{ \Carbon\Carbon::parse($usulan->tanggal_kegiatanaan)->format('d M') }} - 
                            {{ \Carbon\Carbon::parse($usulan->sampai_tanggal)->format('d M Y') }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-700">{{ $usulan->lokasi_kegiatan }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button 
                            wire:click="toggleRow({{ $usulan->id }})"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                        >
                            {{ isset($expandedRows[$usulan->id]) ? 'Tutup' : 'Detail' }}
                        </button>
                    </td>
                </tr>
                @if(isset($expandedRows[$usulan->id]))
                    <tr class="bg-blue-50">
                        <td colspan="6" class="px-6 py-4">
                            <!-- Tabel Detail -->
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-blue-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-bold text-blue-800">Nama Pegawai</th>
                                        <th class="px-4 py-2 text-left text-xs font-bold text-blue-800">NIP</th>
                                        <th class="px-4 py-2 text-left text-xs font-bold text-blue-800">Jabatan</th>
                                        <th class="px-4 py-2 text-center text-xs font-bold text-blue-800">Status</th>
                                        <th class="px-4 py-2 text-center text-xs font-bold text-blue-800">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($usulan->usulanPegawais as $pegawai)
                                    <tr class="border-b border-blue-100">
                                        <td class="px-4 py-3 text-sm">{{ $pegawai->kepegawaian->nama ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $pegawai->kepegawaian->nip ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $pegawai->kepegawaian->jabatan ?? '-' }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs rounded">
                                                {{ $pegawai->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <a href="{{ route('keuangans.bayar', ['usulan_id' => $usulan->id, 'pegawai_id' => $pegawai->pegawai_id]) }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs inline-block">
                                                Bayar
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-3 text-sm text-gray-500 text-center">
                                            Tidak ada pegawai yang di-approve
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    @endif
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">
                        Data tidak ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
                
        </table>
    </div>

    <div class="mt-4">
        {{ $usulans->links() }}
    </div>
</div>