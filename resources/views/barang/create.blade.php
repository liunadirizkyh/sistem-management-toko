<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Barang Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                           Terdapat kesalahan pada input Anda.
                        </div>
                    @endif

                    <form action="{{ route('barang.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nama_barang" class="block font-medium text-sm text-gray-700">Nama Barang</label>
                                <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @error('nama_barang')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                             <div>
                                <label for="kode_barang" class="block font-medium text-sm text-gray-700">Kode Barang (Opsional)</label>
                                <input type="text" name="kode_barang" id="kode_barang" value="{{ old('kode_barang') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
                                @error('kode_barang')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="satuan" class="block font-medium text-sm text-gray-700">Satuan (pcs, sak, m, kg, dll)</label>
                                <input type="text" name="satuan" id="satuan" value="{{ old('satuan') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                @error('satuan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="stok" class="block font-medium text-sm text-gray-700">Stok Awal</label>
                                <input type="number" name="stok" id="stok" value="{{ old('stok', 0) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                @error('stok')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                             <div>
                                <label for="harga_beli" class="block font-medium text-sm text-gray-700">Harga Beli (Modal)</label>
                                <input type="number" name="harga_beli" id="harga_beli" value="{{ old('harga_beli') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                @error('harga_beli')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="harga_jual" class="block font-medium text-sm text-gray-700">Harga Jual</label>
                                <input type="number" name="harga_jual" id="harga_jual" value="{{ old('harga_jual') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                @error('harga_jual')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('barang.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan Barang
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>