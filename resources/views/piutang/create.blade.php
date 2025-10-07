<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('piutang.index') }}" class="hover:underline">
                {{ __('Piutang Pelanggan') }}
            </a>
            <span class="mx-2 font-sans">&gt;</span>
            <span>
                {{ __('Catat Transaksi Baru') }}
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

                    <form action="{{ route('piutang.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <label for="pelanggan_id_existing" class="block font-medium text-sm text-gray-700">Pilih Pelanggan (Jika Sudah Ada)</label>
                                <select name="pelanggan_id_existing" id="pelanggan_id_existing" class="w-full block mt-1">
                                    <option></option>
                                    @foreach($pelanggans as $pelanggan)
                                        <option value="{{ $pelanggan->id }}" {{ old('pelanggan_id_existing') == $pelanggan->id ? 'selected' : '' }}>{{ $pelanggan->nama_pelanggan }}</option>
                                    @endforeach
                                </select>
                                @error('pelanggan_id_existing')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="pelanggan_id_new" class="block font-medium text-sm text-gray-700">Atau Input Nama Pelanggan Baru</label>
                                <input type="text" name="pelanggan_id_new" id="pelanggan_id_new" value="{{ old('pelanggan_id_new') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
                                @error('pelanggan_id_new')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="tanggal" class="block font-medium text-sm text-gray-700">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', now()->toDateString()) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                            </div>

                            <div>
                                <label for="tipe" class="block font-medium text-sm text-gray-700">Tipe Transaksi</label>
                                <select name="tipe" id="tipe" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                    <option value="pengambilan" {{ old('tipe') == 'pengambilan' ? 'selected' : '' }}>Pengambilan Barang (Menambah Hutang)</option>
                                    <option value="pembayaran" {{ old('tipe') == 'pembayaran' ? 'selected' : '' }}>Deposit / Pembayaran (Mengurangi Hutang)</option>
                                </select>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="deskripsi" class="block font-medium text-sm text-gray-700">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required placeholder="Contoh: Semen 10 sak, Bata 500 pcs atau Pembayaran Tunai">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="jumlah_formatted" class="block font-medium text-sm text-gray-700">Jumlah (Rp)</label>
                                <input type="text" id="jumlah_formatted" inputmode="numeric" class="number-format block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('jumlah') ? number_format(old('jumlah'), 0, ',', '.') : '' }}" required>
                                <input type="hidden" name="jumlah" id="jumlah" value="{{ old('jumlah') }}">
                                @error('jumlah')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 pt-4 space-x-2">
                            <a href="{{ route('piutang.index') }}" class="hover:text-gray-900 text-gray-700 font-bold py-2 px-4 rounded-lg text-sm">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-sm">
                                Simpan Transaksi
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
            $('#pelanggan_id_existing').select2({ placeholder: "-- Pilih Pelanggan --" });
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