<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session()->has('message'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Manajemen Hak Akses Pegawai</h2>
                <div class="w-1/3">
                    <input type="text" wire:model.live="search" placeholder="Cari nama pegawai..." 
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pegawai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Role / Hak Akses</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pegawais as $p)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $p->nama }}</div>
                                <div class="text-sm text-gray-500">{{ $p->email ?? $p->nip }}</div>
                                
                                @if($p->user) {{-- Mengecek relasi user, bukan cuma kolom user_id --}}
                                    <span class="text-[10px] text-green-600 font-bold uppercase tracking-tighter">Sudah Memiliki Akun</span>
                                @else
                                    <span class="text-[10px] text-red-500 font-bold uppercase tracking-tighter">Belum Memiliki Akun</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $p->jabatan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($p->user)
                                    @forelse($p->user->getRoleNames() as $roleName)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 border border-indigo-200 mr-1">
                                            {{ strtoupper($roleName) }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400 italic">Belum pilih role</span>
                                    @endforelse
                                @else
                                    <span class="text-xs text-gray-300">Akses tidak tersedia</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center gap-2">
                                    {{-- Tombol Reset Password hanya muncul jika ada user --}}
                                    @if($p->user_id)
                                        <button 
                                            wire:click="resetPassword({{ $p->user_id }})" 
                                            wire:confirm="Apakah Anda yakin ingin meriset password pegawai ini menjadi 'password123'?"
                                            class="inline-flex items-center px-3 py-1 bg-amber-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-600 transition duration-150 shadow-sm"
                                            title="Reset ke password123"
                                        >
                                            Reset PW
                                        </button>
                                    @endif

                                    <button wire:click="aturRole({{ $p->id }})" 
                                        class="inline-flex items-center px-3 py-1 bg-white border border-indigo-600 rounded-md font-semibold text-xs text-indigo-600 uppercase tracking-widest hover:bg-indigo-50 transition duration-150">
                                        {{ $p->user_id ? 'Edit Akses' : 'Buat Akun & Role' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $pegawais->links() }}
            </div>
        </div>
    </div>

    @if($selectedPegawai)
    <div class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50">
        <div class="fixed inset-0 transform transition-all">
            <div class="absolute inset-0 bg-gray-900 opacity-50"></div>
        </div>

        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:mx-auto">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-indigo-600">
                <h3 class="text-lg font-bold text-white">Konfigurasi Akses: {{ $selectedPegawai->nama }}</h3>
                <a href="{{ route('admin.manajemen-akses') }}" wire:navigate class="text-white hover:text-gray-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>
            
            <div class="p-6">
                @if(!$selectedPegawai->user_id)
                    <div class="mb-4 p-3 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 text-xs">
                        <strong>Info:</strong> Pegawai ini belum memiliki akun login. Sistem akan otomatis membuatkan akun dengan password default <strong>"password123"</strong> saat Anda menyimpan.
                    </div>
                @endif

                <p class="text-sm text-gray-600 mb-4 font-semibold">Pilih Role / Peran:</p>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($all_roles as $role)
                    <div class="flex items-center p-2 border border-gray-100 rounded hover:bg-gray-50">
                        <input type="checkbox" value="{{ $role->name }}" wire:model="roles_dipilih" 
                            id="role-{{ $role->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <label for="role-{{ $role->id }}" class="ml-2 text-sm text-gray-700 cursor-pointer">{{ ucfirst($role->name) }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
            <a href="{{ route('admin.manajemen-akses') }}" class="px-4 py-2 border rounded">Batal</a>
                <button wire:click="simpanAkses" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150">
                    {{ $selectedPegawai->user_id ? 'Simpan Perubahan' : 'Konfirmasi & Buat Akun' }}
                </button>
            </div>
        </div>
    </div>
    @endif
</div>