<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('kode-barang.index') }}" class="hover:underline">
                {{ __('Manajemen Kode Barang') }}
            </a>
            <span class="mx-2 font-sans">&gt;</span>
            <span>
                {{ __('Edit Kode Barang') }}
            </span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('kode-barang.update', $kodeBarang->id) }}" method="POST" id="update-form">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label for="kode" class="block font-medium text-sm text-gray-700">Kode Barang</label>
                                <input type="text" name="kode" id="kode" value="{{ old('kode', $kodeBarang->kode) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                @error('kode')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="harga_modal_formatted" class="block font-medium text-sm text-gray-700">Harga Modal</label>
                                <input type="text" id="harga_modal_formatted" inputmode="numeric" class="number-format block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ number_format(old('harga_modal', $kodeBarang->harga_modal), 0, ',', '.') }}" required>
                                <input type="hidden" name="harga_modal" id="harga_modal" value="{{ old('harga_modal', $kodeBarang->harga_modal) }}">
                                @error('harga_modal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </form>

                    <div class="flex items-center justify-between mt-6 pt-4">
                        <div>
                            <form action="{{ route('kode-barang.destroy', $kodeBarang->id) }}" method="POST" onsubmit="return confirm('Anda YAKIN ingin menghapus kode ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg text-sm">
                                    Hapus
                                </button>
                            </form>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('kode-barang.index') }}" class="hover:text-gray-900 text-gray-700 font-bold py-2 px-4 rounded-lg text-sm">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-sm" form="update-form">
                                Update
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