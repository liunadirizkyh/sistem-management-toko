<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('piutang.index') }}" class="hover:underline">Piutang Pelanggan</a>
            <span class="mx-2 font-sans">&gt;</span>
            <a href="{{ route('piutang.show', $piutang->pelanggan) }}" class="hover:underline">{{ $piutang->pelanggan->nama_pelanggan }}</a>
            <span class="mx-2 font-sans">&gt;</span>
            <span>Edit Transaksi</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <ul> @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
                        </div>
                    @endif

                    <form action="{{ route('piutang.update', $piutang) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Pelanggan</label>
                                <input type="text" value="{{ $piutang->pelanggan->nama_pelanggan }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 bg-gray-100" readonly>
                            </div>
                            <div>
                                <label for="tanggal" class="block font-medium text-sm text-gray-700">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $piutang->tanggal) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Tipe Transaksi</label>
                                <select name="tipe" id="tipe" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                    <option value="pengambilan" {{ old('tipe', $piutang->tipe) == 'pengambilan' ? 'selected' : '' }}>Pengambilan Barang (Menambah Hutang)</option>
                                    <option value="pembayaran" {{ old('tipe', $piutang->tipe) == 'pembayaran' ? 'selected' : '' }}>Deposit / Pembayaran (Mengurangi Hutang)</option>
                                </select>
                            </div>
                            <div>
                                <label for="deskripsi" class="block font-medium text-sm text-gray-700">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>{{ old('deskripsi', $piutang->deskripsi) }}</textarea>
                            </div>
                            <div>
                                <label for="jumlah_formatted" class="block font-medium text-sm text-gray-700">Jumlah (Rp)</label>
                                <input type="text" id="jumlah_formatted" inputmode="numeric" class="number-format block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ number_format(old('jumlah', $piutang->jumlah), 0, ',', '.') }}" required>
                                <input type="hidden" name="jumlah" id="jumlah" value="{{ old('jumlah', $piutang->jumlah) }}">
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 border-t pt-4 space-x-2">
                            <a href="{{ route('piutang.show', $piutang->pelanggan) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg text-sm">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-sm">
                                Update Transaksi
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