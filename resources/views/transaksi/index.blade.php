<x-app-layout>
    <x-slot name="header">
        <div class="relative flex items-center h-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Riwayat Transaksi') }}
            </h2>
            <div class="absolute top-0 right-0 h-full flex items-center">
                <a href="{{ route('transaksi.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    + Buat Transaksi Baru
                </a>
            </div>
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
                    
                    {{-- Form Filter & Pencarian --}}
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
                                    <input type="search" name="search" id="search" placeholder="Cari No. Transaksi..." value="{{ $search ?? '' }}" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <button type="submit" class="w-full sm:w-auto bg-blue-500 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-700 text-sm">
                                        Cari
                                    </button>
                                    <a href="{{ route('transaksi.index') }}" class="w-full sm:w-auto text-center bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-md hover:bg-gray-300 text-sm">
                                        Reset
                                    </a>
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
                                    <th class="py-2 px-4 border-b text-right">Total Belanja</th>
                                    <th class="py-2 px-4 border-b text-right">Uang Bayar</th>
                                    <th class="py-2 px-4 border-b text-right">Uang Kembali</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaksis as $transaksi)
                                    <tr class="hover:bg-gray-100 cursor-pointer" onclick="window.location='{{ route('transaksi.edit', $transaksi) }}'">
                                        <td class="py-2 px-4 border-b align-middle font-mono text-sm">{{ $transaksi->nomor_transaksi }}</td>
                                        <td class="py-2 px-4 border-b align-middle">{{ $transaksi->created_at->format('d M Y, H:i') }}</td>
                                        <td class="py-2 px-4 border-b align-middle text-right">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                                        <td class="py-2 px-4 border-b align-middle text-right">Rp {{ number_format($transaksi->uang_bayar, 0, ',', '.') }}</td>
                                        <td class="py-2 px-4 border-b align-middle text-right">Rp {{ number_format($transaksi->uang_kembali, 0, ',', '.') }}</td>
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