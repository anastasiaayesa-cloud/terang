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
                                                    <td class="px-4 py-3 text-sm italic text-gray-500">
                                                        {{-- DISINI SUDAH DIUBAH KE reject_reason --}}
                                                        @if ($usulanPegawai->status === 'rejected')
                                                            {{ $usulanPegawai->reject_reason ?? 'Tanpa alasan' }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 text-sm">
                                                        @if ($usulanPegawai->status === 'pending')
                                                            <button wire:click="approve({{ $usulanPegawai->id }})" wire:confirm="Terima pegawai ini?" title="Setujui" class="mr-2 cursor-pointer">✅</button>
                                                            
                                                            <button wire:click="confirmReject({{ $usulanPegawai->id }})" title="Tolak" class="mr-2 cursor-pointer">❌</button>
                                                        @endif
                                                        <button wire:click="delete({{ $usulanPegawai->id }})" wire:confirm="Hapus pegawai dari usulan ini?" title="Hapus" class="cursor-pointer">🗑️</button>
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

    {{-- MODAL ALASAN PENOLAKAN --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('showModal', false)"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-red-100 sm:mx-0">
                                <span class="text-red-600 font-bold">!</span>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Penolakan</h3>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan (Wajib)</label>
                                    <textarea 
                                        wire:model="rejected_reason" 
                                        rows="3" 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 @error('rejected_reason') border-red-500 @enderror"
                                        placeholder="Tulis alasan penolakan..."></textarea>
                                    
                                    @error('rejected_reason') 
                                        <span class="text-red-500 text-xs mt-1 block font-bold">{{ $message }}</span> 
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button wire:click="processReject" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:w-auto sm:text-sm">
                            Simpan Penolakan
                        </button>
                        <button wire:click="$set('showModal', false)" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>