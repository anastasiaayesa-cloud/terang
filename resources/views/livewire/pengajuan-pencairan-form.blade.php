<div>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        {{ $pengajuan_id ? 'Edit Pengajuan' : 'Pengajuan Pencairan Dana Baru' }}
                    </h2>

                    {{-- Flash Messages --}}
                    @if (session()->has('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @error('selectedBukti')
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ $message }}
                        </div>
                    @enderror

                    <form wire:submit="submit">
                        {{-- Pilih Perencanaan --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Perencanaan</label>
                            <select wire:model.live="perencanaan_id"
                                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih Perencanaan --</option>
                                @foreach ($perencanaanList as $perencanaan)
                                    <option value="{{ $perencanaan->id }}">{{ $perencanaan->nama_komponen }}</option>
                                @endforeach
                            </select>
                            @error('perencanaan_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Bukti Pengeluaran --}}
                        @if (count($buktiList) > 0)
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Bukti Pengeluaran yang Dicairkan
                                </label>
                                <div class="border rounded-lg overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Pilih</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Tipe</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Nominal</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($buktiList as $bukti)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-2">
                                                        <input type="checkbox"
                                                               wire:model.live="selectedBukti"
                                                               value="{{ $bukti->id }}"
                                                               class="rounded border-gray-300 text-blue-600">
                                                    </td>
                                                    <td class="px-4 py-2 text-sm">{{ $bukti->tipe_bukti_label }}</td>
                                                    <td class="px-4 py-2 text-sm">Rp {{ number_format($bukti->nominal, 0, ',', '.') }}</td>
                                                    <td class="px-4 py-2 text-sm">{{ $bukti->tanggal_bukti?->format('d/m/Y') ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        {{-- Nomor Surat --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat</label>
                            <input type="text"
                                   wire:model="nomor_surat"
                                   class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                            @error('nomor_surat') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Tanggal Pengajuan --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengajuan</label>
                            <input type="date"
                                   wire:model="tanggal_pengajuan"
                                   class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                            @error('tanggal_pengajuan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Uang Harian --}}
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Uang Harian (per hari)</label>
                                <input type="number"
                                       wire:model.live="uang_harian_nominal"
                                       min="0"
                                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                                @error('uang_harian_nominal') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Hari Dinas</label>
                                <input type="number"
                                       wire:model.live="jumlah_hari"
                                       min="0"
                                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                                @error('jumlah_hari') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Total --}}
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Total Bukti Pengeluaran:</span>
                                <span class="font-semibold">Rp {{ number_format($total_nominal - $uang_harian_total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Uang Harian ({{ $uang_harian_nominal }} x {{ $jumlah_hari }} hari):</span>
                                <span class="font-semibold">Rp {{ number_format($uang_harian_total, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-t pt-2 flex justify-between text-lg">
                                <span class="font-bold">Total Pengajuan:</span>
                                <span class="font-bold text-blue-600">Rp {{ number_format($total_nominal, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="flex justify-end gap-4">
                            <a href="{{ route('keuangan.pengajuan-pencairans.index') }}"
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                                Batal
                            </a>
                            <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                                {{ $pengajuan_id ? 'Update' : 'Ajukan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>