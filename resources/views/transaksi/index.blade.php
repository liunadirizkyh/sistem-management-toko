<x-app-layout>
    <x-slot name="header">
        <div class="relative flex items-center h-full" x-data="{ showExportModal: false }">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Riwayat Transaksi') }}
            </h2>
            <div class="absolute top-0 right-0 h-full flex items-center space-x-2">
                <!-- Tombol Export (Hitam Putih) -->
                <button @click="showExportModal = true" 
                    class="flex items-center gap-2 bg-white hover:bg-gray-100 text-gray-800 text-sm font-medium px-4 py-2 rounded-lg border border-gray-300 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Data
                </button>

                <!-- Tombol Buat Transaksi (Tetap sesuai style sebelumnya) -->
                <a href="{{ route('transaksi.create') }}" 
                    class="flex items-center gap-2 bg-white hover:bg-gray-100 text-gray-800 text-sm font-medium px-4 py-2 rounded-lg border border-gray-300 transition shadow-sm">
                    + Buat Transaksi Baru
                </a>
            </div>

            <!-- MODAL EXPORT -->
            <template x-teleport="body">
                <div x-show="showExportModal" 
                    class="fixed inset-0 z-[99] flex items-center justify-center" 
                    x-cloak>
                    <!-- Backdrop -->
                    <div x-show="showExportModal" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        @click="showExportModal = false" 
                        class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"></div>

                    <!-- Modal Content -->
                    <div x-show="showExportModal"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="relative w-full max-w-md bg-white rounded-xl shadow-2xl p-6 mx-4">
                        
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-900 tracking-tight mx-auto">Export Laporan</h3>
                            <button @click="showExportModal = false" class="text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <form action="{{ route('transaksi.export') }}" method="GET" @submit="showExportModal = false">
                            <!-- Hidden search agar hasil export sinkron dengan pencarian saat ini -->
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Mulai Tanggal</label>
                                    <input type="date" name="from_date" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-gray-800 focus:border-gray-800 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Sampai Tanggal</label>
                                    <input type="date" name="to_date" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-gray-800 focus:border-gray-800 text-sm">
                                </div>
                                <p class="text-[10px] text-gray-500 italic">* Kosongkan tanggal untuk menarik seluruh data transaksi.</p>
                            </div>

                            <div class="mt-8 flex flex-col sm:flex-row-reverse gap-2">
                                <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-bold py-2 px-4 rounded-lg text-sm transition">
                                    Download Excel
                                </button>
                                <button type="button" @click="showExportModal = false" class="w-full bg-white hover:bg-gray-50 text-gray-700 font-bold py-2 px-4 rounded-lg border border-gray-300 text-sm transition">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </template>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="mb-4">
                        <form action="{{ route('transaksi.index') }}" method="GET">
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="flex items-center space-x-2">
                                    <label for="per_page" class="text-sm font-medium text-gray-700">Tampilkan:</label>
                                    <select name="per_page" id="per_page" class="rounded-md border-gray-300 shadow-sm text-sm" onchange="this.form.submit()">
                                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                    <span class="text-sm text-gray-700">data</span>
                                </div>
                                
                                <div class="relative flex-grow">
                                    <input type="search" name="search" id="search" placeholder="Cari No. Transaksi/Pelanggan..." value="{{ $search ?? '' }}" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                </div>
                                
                                <div class="flex items-center">
                                    <button type="submit" class="w-full sm:w-auto bg-gray-800 text-white font-semibold py-2 px-6 rounded-md hover:bg-gray-700 text-sm transition-colors">
                                        Search
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white table-auto">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">No. Transaksi</th>
                                    <th class="py-2 px-4 border-b text-left">Tanggal</th>
                                    <th class="py-2 px-4 border-b text-left">Pelanggan</th>
                                    <th class="py-2 px-4 border-b text-right">Total Belanja</th>
                                    <th class="py-2 px-4 border-b text-center">Metode Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaksis as $transaksi)
                                    <tr class="hover:bg-gray-100 cursor-pointer" onclick="window.location='{{ route('transaksi.edit', $transaksi) }}'">
                                        <td class="py-2 px-4 border-b align-middle font-mono text-sm">{{ $transaksi->nomor_transaksi }}</td>
                                        <td class="py-2 px-4 border-b align-middle">{{ $transaksi->created_at->format('d M Y, H:i') }}</td>
                                        <td class="py-2 px-4 border-b align-middle font-semibold">{{ $transaksi->nama_pelanggan ?? 'Umum' }}</td>
                                        <td class="py-2 px-4 border-b align-middle text-right">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                                        <td class="py-2 px-4 border-b align-middle text-center">
                                                @if($transaksi->metode_pembayaran == 'transfer' && $transaksi->via_bank)
                                                {{ ucfirst($transaksi->metode_pembayaran) }} - {{ $transaksi->via_bank }}
                                                @else
                                                {{ ucfirst($transaksi->metode_pembayaran) }}
                                                @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-gray-500">
                                            Tidak ada transaksi yang cocok dengan pencarian Anda.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $transaksis->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>