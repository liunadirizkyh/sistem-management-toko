<x-app-layout>
    <x-slot name="header">
        <div class="relative flex items-center h-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Piutang Pelanggan') }}
            </h2>
            <div class="absolute top-0 right-0 h-full flex items-center">
                <a href="{{ route('piutang.create') }}" class="flex items-center gap-2 bg-white hover:bg-gray-100 text-gray-800 text-sm font-medium px-4 py-2 rounded-lg border border-gray-300 transition shadow-sm">
                    + Catat Transaksi
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
                            <ul> @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                        <form action="{{ route('piutang.index') }}" method="GET">
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
                                    <input type="search" name="search" id="search" placeholder="Cari Nama Pelanggan..." value="{{ $search ?? '' }}" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                </div>

                                <div>
                                    <select name="status" class="rounded-md border-gray-300 shadow-sm text-sm" onchange="this.form.submit()">
                                        <option value="">Semua Saldo</option>
                                        <option value="hutang" {{ $status == 'hutang' ? 'selected' : '' }}>Punya Hutang</option>
                                        <option value="deposit" {{ $status == 'deposit' ? 'selected' : '' }}>Punya Deposit</option>
                                    </select>
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
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200 text-xs text-gray-600 uppercase">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">Nama Pelanggan</th>
                                    <th class="py-2 px-4 border-b text-right w-1/3">Sisa Hutang / Deposit</th>
                                    @role('admin')
                                    <th class="py-2 px-4 border-b text-center w-32">Aksi</th>
                                    @endrole
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse ($pelanggans as $pelanggan)
                                    <tr class="hover:bg-gray-100">
                                        <td class="py-3 px-4 border-b align-middle font-semibold cursor-pointer" onclick="window.location='{{ route('piutang.show', $pelanggan) }}'">
                                            {{ $pelanggan->nama_pelanggan }}
                                        </td>
                                        <td class="py-3 px-4 border-b align-middle text-right font-semibold cursor-pointer" onclick="window.location='{{ route('piutang.show', $pelanggan) }}'">
                                            @if($pelanggan->saldo > 0)
                                                <span class="px-2 py-1 font-semibold text-xs rounded-full bg-red-100 text-red-800">
                                                    Rp {{ number_format($pelanggan->saldo, 0, ',', '.') }}
                                                </span>
                                            @elseif($pelanggan->saldo < 0)
                                                <span class="px-2 py-1 font-semibold text-xs rounded-full bg-green-100 text-green-800">
                                                    Rp {{ number_format(abs($pelanggan->saldo), 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="px-2 py-1 font-semibold text-xs rounded-full bg-gray-200 text-gray-800">
                                                    Rp 0
                                                </span>
                                            @endif
                                        </td>
                                        @role('admin')
                                        <td class="py-3 px-4 border-b align-middle text-center">
                                            <form action="{{ route('pelanggan.destroy', $pelanggan) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus pelanggan ini beserta seluruh riwayatnya?');" onclick="event.stopPropagation()">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-semibold">Hapus</button>
                                            </form>
                                        </td>
                                        @endrole
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-gray-500">Tidak ada data pelanggan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        {{ $pelanggans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>