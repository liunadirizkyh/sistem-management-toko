<x-app-layout>
    <x-slot name="header">
        <div class="relative flex items-center h-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Barang') }}
            </h2>
            
            @role('admin')
            <div class="absolute top-0 right-0 h-full flex items-center space-x-2">
                <button type="button" data-url="{{ route('barang.print', ['search' => request('search')]) }}" class="print-data-btn flex items-center gap-2 bg-white hover:bg-gray-100 text-gray-800 text-sm font-medium px-4 py-2 rounded-lg border border-gray-300 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print Data
                </button>

                <a href="{{ route('barang.create') }}" 
                    class="flex items-center gap-2 bg-white hover:bg-gray-100 text-gray-800 text-sm font-medium px-4 py-2 rounded-lg border border-gray-300 transition shadow-sm">
                    + Tambah Barang
                </a>
            </div>
            @endrole
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
                        <form action="{{ route('barang.index') }}" method="GET">
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
                                    <input type="search" name="search" id="search" placeholder="Cari Nama/Kode Barang..." value="{{ $search ?? '' }}" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
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
                        <table class="min-w-full bg-white table-fixed">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="w-1/6 py-2 px-4 border-b text-left align-middle">Kode Barang</th>
                                    <th class="w-1/4 py-2 px-4 border-b text-left align-middle">Nama Barang</th>
                                    <th class="w-1/12 py-2 px-4 border-b text-center align-middle">Lokasi</th>
                                    <th class="w-1/12 py-2 px-4 border-b text-center align-middle">Satuan</th>
                                    <th class="w-1/12 py-2 px-4 border-b text-center align-middle">Stok</th>
                                    <th class="w-1/6 py-2 px-4 border-b text-right align-middle">Harga Beli</th>
                                    <th class="w-1/6 py-2 px-4 border-b text-right align-middle">Harga Jual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($barangs as $barang)
                                    <tr class="hover:bg-gray-100 cursor-pointer" onclick="window.location='{{ route('barang.edit', $barang->id) }}'">
                                        <td class="py-2 px-4 border-b align-middle">{{ optional($barang->kodeBarang)->kode ?? '-' }}</td>
                                        <td class="py-2 px-4 border-b align-middle font-bold">{{ $barang->nama_barang }}</td>
                                        <td class="py-2 px-4 border-b text-center align-middle">{{ $barang->lokasi_barang ?? '-' }}</td>
                                        <td class="py-2 px-4 border-b text-center align-middle">{{ $barang->satuan }}</td>
                                        <td class="py-2 px-4 border-b text-center align-middle font-bold">{{ $barang->stok }}</td>
                                        <td class="py-2 px-4 border-b text-right align-middle">Rp {{ number_format(optional($barang->kodeBarang)->harga_modal, 0, ',', '.') }}</td>
                                        <td class="py-2 px-4 border-b text-right align-middle">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada barang yang cocok.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        {{ $barangs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('.print-data-btn').on('click', function(e) {
                e.preventDefault();
                const url = $(this).data('url');

                $('iframe[name="print-frame"]').remove();

                const iframe = $('<iframe>', {
                    name: 'print-frame',
                    src: url,
                    style: 'display:none;'
                }).appendTo('body');

                iframe.on('load', function() {
                    try {
                        this.contentWindow.focus();
                        this.contentWindow.print();
                    } catch (e) {
                        console.error("Gagal melakukan print:", e);
                        alert("Gagal membuka dialog cetak. Pastikan browser Anda tidak memblokir popup.");
                    }
                    
                    setTimeout(() => $(this).remove(), 1000);
                });
            });
        });
    </script>
    @endpush

</x-app-layout>