<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-between mb-4">
                        <h3 class="text-lg font-bold">Daftar Barang</h3>
                        <a href="{{ route('barang.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Tambah Barang
                        </a>
                    </div>
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white table-fixed">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="w-1/6 py-2 px-4 border-b">Kode Barang</th>
                                    <th class="w-1/3 py-2 px-4 border-b">Nama Barang</th>
                                    <th class="w-1/12 py-2 px-4 border-b">Satuan</th>
                                    <th class="w-1/12 py-2 px-4 border-b">Stok</th>
                                    <th class="w-1/6 py-2 px-4 border-b">Harga Beli</th>
                                    <th class="w-1/6 py-2 px-4 border-b">Harga Jual</th>
                                    <th class="w-1/6 py-2 px-4 border-b">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($barangs as $barang)
                                    <tr class="hover:bg-gray-100">
                                        <td class="py-2 px-4 border-b">{{ $barang->kode_barang ?? '-' }}</td>
                                        <td class="py-2 px-4 border-b">{{ $barang->nama_barang }}</td>
                                        <td class="py-2 px-4 border-b text-center">{{ $barang->satuan }}</td>
                                        <td class="py-2 px-4 border-b text-center">{{ $barang->stok }}</td>
                                        <td class="py-2 px-4 border-b text-right">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                                        <td class="py-2 px-4 border-b text-right">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                                        <td class="py-2 px-4 border-b text-center">
                                            <a href="{{ route('barang.edit', $barang->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-2">Edit</a>
                                            <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">Tidak ada data barang.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>