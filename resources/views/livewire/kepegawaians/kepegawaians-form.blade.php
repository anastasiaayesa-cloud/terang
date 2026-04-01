<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Pegawai') }}
    </h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 max-w-2xl">

                <h2 class="text-xl mb-4">
                    {{ $kepegawaian ? 'Edit Pegawai' : 'Tambah Pegawai' }}
                </h2>

                @if (session('success'))
                    <div class="mb-4 text-green-700">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="mb-4 text-red-700">{{ session('error') }}</div>
                @endif

                <form wire:submit.prevent="submit" autocomplete="off">

                    {{-- NAMA --}}
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" wire:model.defer="nama" class="w-full border rounded px-3 py-2">
                        @error('nama') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- NIP --}}
                    <div class="mb-3">
                        <label>NIP</label>
                        <input type="text" wire:model.defer="nip" class="w-full border rounded px-3 py-2">
                    </div>

                    {{-- JABATAN --}}
                    <div class="mb-3">
                        <label>Jabatan</label>
                        <input type="text" wire:model.defer="jabatan" class="w-full border rounded px-3 py-2">
                    </div>

                    {{-- PANGKAT --}}
                    <div class="mb-3">
                        <label>Pangkat</label>
                        <select wire:model.defer="pangkat_id" class="w-full border rounded px-3 py-2">
                            <option value="">-- Pilih Pangkat --</option>
                            @foreach ($pangkatList as $pangkat)
                                <option value="{{ $pangkat->id }}">{{ $pangkat->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TEMPAT LAHIR --}}
                    <div class="mb-3">
                        <label>Tempat Lahir</label>
                        <input type="text" wire:model.defer="tempat_lahir" class="w-full border rounded px-3 py-2">
                    </div>

                    {{-- TANGGAL LAHIR --}}
                    <div class="mb-3">
                        <label>Tanggal Lahir</label>
                        <input type="date" wire:model.defer="tgl_lahir" class="w-full border rounded px-3 py-2">
                    </div>

                    {{-- JENIS KELAMIN --}}
                    <div class="mb-3">
                        <label>Jenis Kelamin</label>
                        <select wire:model.defer="jenis_kelamin" class="w-full border rounded px-3 py-2">
                            <option value="">-- Pilih --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- AGAMA --}}
                    <div class="mb-3">
                        <label>Agama</label>
                        <select wire:model.defer="agama" class="w-full border rounded px-3 py-2">
                            <option value="">-- Pilih --</option>
                            <option>Islam</option>
                            <option>Kristen Katolik</option>
                            <option>Kristen Protestan</option>
                            <option>Hindu</option>
                            <option>Buddha</option>
                            <option>Konghucu</option>
                        </select>
                        @error('agama') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- INSTANSI --}}
                    <div class="mb-3">
                        <label>Instansi</label>
                        <select wire:model.defer="instansi_id" class="w-full border rounded px-3 py-2">
                            <option value="">-- Pilih --</option>
                            @foreach ($instansiList as $instansi)
                                <option value="{{ $instansi->id }}">{{ $instansi->nama_instansi }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- HP --}}
                    <div class="mb-3">
                        <label>No HP</label>
                        <input type="text" wire:model.defer="hp" class="w-full border rounded px-3 py-2">
                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" wire:model.defer="email" class="w-full border rounded px-3 py-2">
                        @error('email') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- NPWP --}}
                    <div class="mb-3">
                        <label>NPWP</label>
                        <input type="text" wire:model.defer="npwp" class="w-full border rounded px-3 py-2">
                    </div>

                    {{-- BANK --}}
                    <div class="mb-3">
                        <label>Bank</label>
                        <select wire:model.defer="bank_id" class="w-full border rounded px-3 py-2">
                            <option value="">-- Pilih --</option>
                            @foreach ($bankList as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- NO REK --}}
                    <div class="mb-3">
                        <label>No Rekening</label>
                        <input type="text" wire:model.defer="no_rek" class="w-full border rounded px-3 py-2">
                    </div>

                    {{-- PENDIDIKAN --}}
                    <div class="mb-3">
                        <label>Pendidikan</label>
                        <select wire:model.defer="pendidikan_id" class="w-full border rounded px-3 py-2">
                            <option value="">-- Pilih --</option>
                            @foreach ($pendidikanList as $pendidikan)
                                <option value="{{ $pendidikan->id }}">{{ $pendidikan->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- IS BPMP (BARU) --}}
                    <div class="mb-3">
                        <label>BPMP</label>
                        <select wire:model.defer="is_bpmp" class="w-full border rounded px-3 py-2">
                            <option value="">-- Pilih --</option>
                            <option value="1">Ya</option>
                            <option value="0">Tidak</option>
                        </select>
                    </div>

                    {{-- BUTTON --}}
                    <div class="flex gap-2 mt-4">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">
                            {{ $kepegawaian ? 'Simpan Perubahan' : 'Simpan' }}
                        </button>

                        @if($kepegawaian)
                            <button type="button" wire:click="delete"
                                class="px-4 py-2 bg-red-600 text-white rounded">
                                Hapus
                            </button>
                        @endif

                        <a href="{{ route('kepegawaians.index') }}"
                           class="px-4 py-2 border rounded">
                           Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>