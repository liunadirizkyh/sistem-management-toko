<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('barang.index') }}" class="hover:underline">
                {{ __('Manajemen Barang') }}
            </a>
            <span class="mx-2 font-sans">&gt;</span>
            <span>
                {{ __('Edit Data Barang') }}
            </span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('barang.update', $barang->id) }}" method="POST" id="update-form">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Input fields (tidak ada perubahan di sini) --}}
                            <div>
                                <label for="nama_barang" class="block font-medium text-sm text-gray-700">Nama Barang</label>
                                <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                            </div>
                            <div>
                                <label for="kode_barang" class="block font-medium text-sm text-gray-700">Kode Barang (Opsional)</label>
                                <input type="text" name="kode_barang" id="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
                            </div>
                            <div>
                                <label for="satuan" class="block font-medium text-sm text-gray-700">Satuan</label>
                                <input type="text" name="satuan" id="satuan" value="{{ old('satuan', $barang->satuan) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                            </div>
                            <div>
                                <label for="stok" class="block font-medium text-sm text-gray-700">Stok</label>
                                <input type="number" name="stok" id="stok" value="{{ old('stok', $barang->stok) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                            </div>
                            <div>
                                <label for="harga_beli" class="block font-medium text-sm text-gray-700">Harga Beli (Modal)</label>
                                <input type="number" name="harga_beli" id="harga_beli" value="{{ old('harga_beli', $barang->harga_beli) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                            </div>
                            <div>
                                <label for="harga_jual" class="block font-medium text-sm text-gray-700">Harga Jual</label>
                                <input type="number" name="harga_jual" id="harga_jual" value="{{ old('harga_jual', $barang->harga_jual) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                            </div>
                        </div>
                        </form>

                    <div class="flex items-center justify-between mt-6 border-t pt-4">
                        <div>
                            <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" onsubmit="return confirm('Anda YAKIN ingin menghapus barang ini? Tindakan ini tidak bisa dibatalkan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Hapus Barang
                                </button>
                            </form>
                        </div>
                        
                        <div class="flex items-center">
                            <a href="{{ route('barang.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" form="update-form">
                                Update Barang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>