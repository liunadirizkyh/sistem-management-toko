<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('hutang-supplier.index') }}" class="hover:underline">
                {{ __('Hutang Supplier') }}
            </a>
            <span class="mx-2 font-sans">&gt;</span>
            <span>
                {{ __('Tambah Hutang Baru') }}
            </span>
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

                    <form action="{{ route('hutang-supplier.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <label for="tanggal_datang" class="block font-medium text-sm text-gray-700">Tanggal Datang</label>
                                <input type="date" name="tanggal_datang" id="tanggal_datang" value="{{ old('tanggal_datang', now()->toDateString()) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                @error('tanggal_datang')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="nama_supplier" class="block font-medium text-sm text-gray-700">Nama Supplier</label>
                                <input type="text" name="nama_supplier" id="nama_supplier" value="{{ old('nama_supplier') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                @error('nama_supplier')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            
                            <div>
                                <label for="kode_nota" class="block font-medium text-sm text-gray-700">Kode Nota</label>
                                <input type="text" name="kode_nota" id="kode_nota" value="{{ old('kode_nota') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                @error('kode_nota')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="tanggal_jatuh_tempo" class="block font-medium text-sm text-gray-700">Tanggal Jatuh Tempo</label>
                                <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                @error('tanggal_jatuh_tempo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="nama_barang" class="block font-medium text-sm text-gray-700">Nama Barang (pisahkan dengan koma jika lebih dari satu)</label>
                                <textarea name="nama_barang" id="nama_barang" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">{{ old('nama_barang') }}</textarea>
                                @error('nama_barang')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <div>
                                    <label for="harga_total_formatted" class="block font-medium text-sm text-gray-700">Total Tagihan</label>
                                    <input type="text" id="harga_total_formatted" inputmode="numeric" class="number-format block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('harga_total') ? number_format(old('harga_total'), 0, ',', '.') : '' }}" required>
                                    <input type="hidden" name="harga_total" id="harga_total" value="{{ old('harga_total') }}">
                                    @error('harga_total')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="jumlah_dibayar_formatted" class="block font-medium text-sm text-gray-700">Jumlah Dibayar (Opsional)</label>
                                    <input type="text" id="jumlah_dibayar_formatted" inputmode="numeric" class="number-format block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('jumlah_dibayar') ? number_format(old('jumlah_dibayar'), 0, ',', '.') : '' }}">
                                    <input type="hidden" name="jumlah_dibayar" id="jumlah_dibayar" value="{{ old('jumlah_dibayar', 0) }}">
                                    @error('jumlah_dibayar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Sisa Tagihan</label>
                                    <div id="sisa-tagihan" class="block mt-1 w-full p-2 bg-gray-200 rounded-md shadow-sm border-gray-300 text-sm">
                                        Rp 0
                                    </div>
                                </div>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="tanggal_bayar" class="block font-medium text-sm text-gray-700">Tanggal Pembayaran Terakhir (Opsional)</label>
                                <input type="date" name="tanggal_bayar" id="tanggal_bayar" value="{{ old('tanggal_bayar') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
                                @error('tanggal_bayar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6 pt-4 space-x-2">
                            <a href="{{ route('hutang-supplier.index') }}" class="hover:text-gray-900 text-gray-700 font-bold py-2 px-4 rounded-lg text-sm">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-sm">
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            function updateSisaTagihan() {
                const total = parseFloat($('#harga_total').val()) || 0;
                const dibayar = parseFloat($('#jumlah_dibayar').val()) || 0;
                const sisa = total - dibayar;
                
                const formattedSisa = new Intl.NumberFormat('id-ID', { 
                    style: 'currency', 
                    currency: 'IDR', 
                    minimumFractionDigits: 0 
                }).format(sisa);

                $('#sisa-tagihan').text(formattedSisa);
            }
            
            updateSisaTagihan();

            $('.number-format').on('input', function() {
                let hiddenInputId = $(this).attr('id').replace('_formatted', '');
                let rawValue = $(this).val().replace(/[^0-9]/g, '');
                $(`#${hiddenInputId}`).val(rawValue);

                if (rawValue) {
                    $(this).val(parseInt(rawValue, 10).toLocaleString('id-ID'));
                } else {
                    $(this).val('');
                }
                updateSisaTagihan();
            });
        });
    </script>
    @endpush
</x-app-layout>