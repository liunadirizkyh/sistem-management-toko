<x-app-layout>
    <x-slot name="header">
        <div class="relative flex items-center h-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Hutang Supplier') }}
            </h2>
            <div class="absolute top-0 right-0 h-full flex items-center">
                <a href="{{ route('hutang-supplier.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    + Tambah Hutang
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
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                        <form action="{{ route('hutang-supplier.index') }}" method="GET">
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="flex items-center space-x-2">
                                    <label for="per_page" class="text-sm font-medium text-gray-700">Tampilkan:</label>
                                    <select name="per_page" id="per_page" class="rounded-md border-gray-300 shadow-sm text-sm" onchange="this.form.submit()">
                                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                    </select>
                                    <span class="text-sm text-gray-700">data</span>
                                </div>
                                
                                <div class="relative flex-grow">
                                    <input type="search" name="search" id="search" placeholder="Cari Supplier/Nota/Barang..." value="{{ $search ?? '' }}" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                </div>

                                <div>
                                    <select name="status" class="rounded-md border-gray-300 shadow-sm text-sm" onchange="this.form.submit()">
                                        <option value="">Semua Status</option>
                                        <option value="belum_dibayar" {{ $status == 'belum_dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                                        <option value="nyicil" {{ $status == 'nyicil' ? 'selected' : '' }}>Nyicil</option>
                                        <option value="lunas" {{ $status == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                    </select>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <button type="submit" class="w-full sm:w-auto bg-blue-500 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-700 text-sm">
                                        Cari
                                    </button>
                                    <a href="{{ route('hutang-supplier.index') }}" class="w-full sm:w-auto text-center bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-md hover:bg-gray-300 text-sm">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white table-auto">
                            <thead class="bg-gray-200 text-xs text-gray-600 uppercase">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left w-32">Tgl Datang</th>
                                    <th class="py-2 px-4 border-b text-left w-48">Supplier</th>
                                    <th class="py-2 px-4 border-b text-left w-40">Kode Nota</th>
                                    <th class="py-2 px-4 border-b text-left">Deskripsi Barang</th>
                                    <th class="py-2 px-4 border-b text-right w-40">Total Tagihan</th>
                                    <th class="py-2 px-4 border-b text-right w-40">Telah Dibayar</th>
                                    <th class="py-2 px-4 border-b text-center w-32">Status</th>
                                    <th class="py-2 px-4 border-b text-right w-32">Jatuh Tempo</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse ($hutangs as $hutang)
                                    <tr class="hover:bg-gray-100 cursor-pointer" onclick="window.location='{{ route('hutang-supplier.edit', $hutang->id) }}'">
                                        <td class="py-2 px-4 border-b align-middle whitespace-nowrap">{{ \Carbon\Carbon::parse($hutang->tanggal_datang)->format('d M Y') }}</td>
                                        <td class="py-2 px-4 border-b align-middle font-semibold">{{ $hutang->nama_supplier }}</td>
                                        <td class="py-2 px-4 border-b align-middle font-mono">{{ $hutang->kode_nota }}</td>
                                        <td class="py-2 px-4 border-b align-middle">{{ \Illuminate\Support\Str::limit($hutang->nama_barang, 40) }}</td>
                                        <td class="py-2 px-4 border-b align-middle text-right font-semibold">Rp {{ number_format($hutang->harga_total, 0, ',', '.') }}</td>
                                        <td class="py-2 px-4 border-b align-middle text-right">Rp {{ number_format($hutang->jumlah_dibayar, 0, ',', '.') }}</td>
                                        <td class="py-2 px-4 border-b align-middle text-center">
                                            <span class="px-2 py-1 font-semibold text-xs rounded-full {{ $hutang->status_color }}">
                                                {{ $hutang->status }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4 border-b align-middle whitespace-nowrap text-right">
                                            {{ \Carbon\Carbon::parse($hutang->tanggal_jatuh_tempo)->format('d M Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-gray-500">Tidak ada data hutang.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        {{ $hutangs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>