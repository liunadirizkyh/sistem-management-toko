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
                    
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                           <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                           </ul>
                        </div>
                    @endif

                    <form action="{{ route('barang.update', $barang->id) }}" method="POST" id="update-form">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <label for="kode_barang_id" class="block font-medium text-sm text-gray-700">Kode Barang</label>
                                <select name="kode_barang_id" id="kode_barang_id" class="w-full block mt-1" required>
                                    <option></option>
                                    @foreach($kodeBarangs as $kode)
                                        <option value="{{ $kode->id }}" data-harga="{{ $kode->harga_modal }}" {{ old('kode_barang_id', $barang->kode_barang_id) == $kode->id ? 'selected' : '' }}>
                                            {{ $kode->kode }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kode_barang_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="nama_barang" class="block font-medium text-sm text-gray-700">Nama Barang</label>
                                <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                @error('nama_barang')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            
                            <div>
                                <label for="harga_beli" class="block font-medium text-sm text-gray-700">Harga Beli (Modal)</label>
                                <input type="text" id="harga_beli" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 bg-gray-100" readonly>
                            </div>

                            <div>
                                <label for="harga_jual_formatted" class="block font-medium text-sm text-gray-700">Harga Jual</label>
                                <input type="text" id="harga_jual_formatted" inputmode="numeric" class="number-format block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ number_format(old('harga_jual', $barang->harga_jual), 0, ',', '.') }}" required>
                                <input type="hidden" name="harga_jual" id="harga_jual" value="{{ old('harga_jual', $barang->harga_jual) }}">
                                @error('harga_jual')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="satuan" class="block font-medium text-sm text-gray-700">Satuan</label>
                                <input type="text" name="satuan" id="satuan" value="{{ old('satuan', $barang->satuan) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                @error('satuan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            
                            <div>
                                <label for="stok_formatted" class="block font-medium text-sm text-gray-700">Stok</label>
                                <input type="text" id="stok_formatted" inputmode="numeric" class="number-format block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ number_format(old('stok', $barang->stok), 0, ',', '.') }}" required>
                                <input type="hidden" name="stok" id="stok" value="{{ old('stok', $barang->stok) }}">
                                @error('stok')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
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

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#kode_barang_id').select2({
                placeholder: "-- Pilih Kode Barang --"
            });

            function updateHargaBeli() {
                const selectedOption = $('#kode_barang_id').find('option:selected');
                const harga = selectedOption.data('harga');

                if (harga) {
                    const formattedHarga = parseInt(harga, 10).toLocaleString('id-ID');
                    $('#harga_beli').val('Rp ' + formattedHarga);
                } else {
                    $('#harga_beli').val('');
                }
            }

            updateHargaBeli();

            $('#kode_barang_id').on('change', function() {
                updateHargaBeli();
            });

            $('.number-format').on('input', function() {
                let hiddenInputId = $(this).attr('id').replace('_formatted', '');
                let rawValue = $(this).val().replace(/[^0-9]/g, '');
                $(`#${hiddenInputId}`).val(rawValue);
                if (rawValue) {
                    $(this).val(parseInt(rawValue, 10).toLocaleString('id-ID'));
                } else {
                    $(this).val('');
                }
            });
        });
    </script>
    @endpush
</x-app-layout>