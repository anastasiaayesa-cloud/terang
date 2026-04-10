<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Usulan Pegawai</h2>
                    </div>

                    <div class="flex gap-4 mb-4">
                        <input type="text" wire:model.live="search" placeholder="Cari nama kegiatan..." class="border rounded px-3 py-2 w-64">
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

                    <div class="space-y-4">
                        @forelse ($usulans as $usulan)
                            <div class="border rounded-lg overflow-hidden">
                                <div class="bg-gray-50 px-4 py-3 flex justify-between items-center">
                                    <div>
                                        <h3 class="font-semibold text-lg">{{ $usulan->nama_kegiatan }}</h3>
                                        <p class="text-sm text-gray-600">
                                            {{ $usulan->tanggal_kegiatan->format('d/m/Y') }} • {{ $usulan->lokasi_kegiatan }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('usulan-pegawais.create', ['usulan_id' => $usulan->id]) }}" class="text-sm bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                            + Tambah Pegawai
                                        </a>
                                        @if ($usulan->status === 'pending')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @elseif ($usulan->status === 'approved')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                        @endif
                                    </div>
                                </div>

                                @if ($usulan->usulanPegawais->count() > 0)
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Pegawai</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status ACC</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Alasan Penolakan</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($usulan->usulanPegawais as $usulanPegawai)
                                                <tr wire:key="up-{{ $usulanPegawai->id }}">
                                                    <td class="px-4 py-3">{{ $usulanPegawai->kepegawaian->nama }}</td>
                                                    <td class="px-4 py-3">
                                                        @if ($usulanPegawai->status === 'pending')
                                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                                        @elseif ($usulanPegawai->status === 'approved')
                                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                                        @else
                                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 text-sm">
                                                        @if ($usulanPegawai->status === 'rejected')
                                                            {{ $usulanPegawai->reject_reason ?? '-' }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 text-sm">
                                                        @if ($usulanPegawai->status === 'pending')
                                                            <button wire:click="approve({{ $usulanPegawai->id }})" wire:confirm="Terima pegawai ini?" class="text-green-600 hover:text-green-900 mr-2">✅</button>
                                                            <button wire:click="reject({{ $usulanPegawai->id }})" wire:confirm="Tolak pegawai ini?" class="text-red-600 hover:text-red-900 mr-2">❌</button>
                                                        @endif
                                                        <button wire:click="delete({{ $usulanPegawai->id }})" wire:confirm="Hapus pegawai dari usulan ini?" class="text-red-600 hover:text-red-900">🗑️</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="px-4 py-6 text-center text-gray-500 text-sm">
                                        Belum ada pegawai yang diajukan.
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                Tidak ada data usulan.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $usulans->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
