<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('kode-barang.index') }}" class="hover:underline">
                {{ __('Manajemen Kode Barang') }}
            </a>
            <span class="mx-2 font-sans">&gt;</span>
            <span>
                {{ __('Tambah Kode Baru') }}
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

                    <form action="{{ route('kode-barang.store') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="kode" class="block font-medium text-sm text-gray-700">Kode Barang</label>
                                <input type="text" name="kode" id="kode" value="{{ old('kode') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                @error('kode')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            
                            <div>
                                <label for="harga_modal_formatted" class="block font-medium text-sm text-gray-700">Harga Modal</label>
                                <input type="text" id="harga_modal_formatted" inputmode="numeric" class="number-format block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('harga_modal') ? number_format(old('harga_modal'), 0, ',', '.') : '' }}" required>
                                <input type="hidden" name="harga_modal" id="harga_modal" value="{{ old('harga_modal') }}">
                                @error('harga_modal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 pt-4">
                            <a href="{{ route('kode-barang.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan Kode
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