<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Usulan Pembayaran 30% Biaya Penginapan') }}
    </h2>
</x-slot>

<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

        <!-- Breadcrumb -->
        <div class="mb-4">
            <a href="{{ route('usulan-pembayarans.index') }}"
               class="text-blue-600 hover:text-blue-800">
                ← Kembali ke Daftar Usulan
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-2">
                    {{ $usulan_id ? 'Edit Usulan Pembayaran' : 'Tambah Usulan Pembayaran' }}
                </h3>
                <p class="text-sm text-gray-600 mb-6">
                    30% dari tarif hotel SBM kota tujuan (lumpsum tanpa kuitansi)
                </p>

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-50 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form wire:submit="submit">

                    <!-- Pilih Perencanaan -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Perencanaan <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="perencanaan_id"
                                class="border rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Perencanaan --</option>
                            @foreach ($perencanaanList as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_komponen }} @if($p->kode)({{ $p->kode }})@endif</option>
                            @endforeach
                        </select>
                        @error('perencanaan_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Pilih Pegawai -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Pegawai <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="pegawai_id"
                                class="border rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach ($pegawaiList as $pegawai)
                                <option value="{{ $pegawai->id }}">{{ $pegawai->nama }} @if($pegawai->nip)({{ $pegawai->nip }})@endif</option>
                            @endforeach
                        </select>
                        @error('pegawai_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Provinsi Tujuan -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Provinsi Tujuan <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="provinsi_tujuan"
                                class="border rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach ($paguList as $pagu)
                                <option value="{{ $pagu->provinsi }}">{{ $pagu->provinsi }}</option>
                            @endforeach
                        </select>
                        @error('provinsi_tujuan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tanggal -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggal Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" wire:model.live="tanggal_mulai"
                                   class="border rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('tanggal_mulai') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggal Selesai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" wire:model.live="tanggal_selesai"
                                   class="border rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('tanggal_selesai') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Persen Klaim -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Persentase Klaim (%)
                        </label>
                        <input type="number" wire:model.live="persen_klaim" min="0" max="100" step="0.01"
                               class="border rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('persen_klaim') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Auto-calculated Info -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-blue-800 mb-3">📊 Detail Perhitungan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-gray-600">Jumlah Malam:</p>
                                <p class="font-semibold text-blue-800">{{ $jumlah_malam }} malam</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Golongan:</p>
                                <p class="font-semibold text-blue-800">{{ $golongan_label ?: '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Tarif Hotel SBM:</p>
                                <p class="font-semibold text-blue-800">Rp {{ number_format($tarif_hotel_sbm, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Nominal per Malam ({{ $persen_klaim }}%):</p>
                                <p class="font-semibold text-blue-800">Rp {{ number_format($nominal_per_malam, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-green-800">💰 TOTAL USULAN:</span>
                            <span class="text-xl font-bold text-green-800">
                                Rp {{ number_format($total_nominal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center space-x-2">
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            {{ $usulan_id ? 'Update' : 'Simpan' }}
                        </button>
                        <a href="{{ route('usulan-pembayarans.index') }}"
                           class="px-4 py-2 border rounded hover:bg-gray-50">
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
