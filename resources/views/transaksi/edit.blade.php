<x-app-layout>
    <x-slot name="header">
        <div class="relative flex items-center h-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <a href="{{ route('transaksi.index') }}" class="hover:underline">
                    Riwayat Transaksi
                </a>
                <span class="mx-2 font-sans">&gt;</span>
                <span>
                    Edit Transaksi
                </span>
            </h2>
            <div class="absolute top-0 right-0 h-full flex items-center">
                <a href="{{ route('transaksi.show', $transaksi) }}" target="_blank" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center no-print">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Nota
                </a>
            </div>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('transaksi.update', $transaksi) }}" method="POST" id="transaction-form">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2">
                                <div class="bg-gray-100 p-4 rounded-lg">
                                    <label for="barang-search" class="block font-medium text-sm text-gray-700 mb-2">Tambah Barang ke Keranjang</label>
                                    <select id="barang-search" class="w-full">
                                        <option></option>
                                        @foreach($barangs as $barang)
                                            <option value="{{ $barang->id }}" 
                                                    data-nama="{{ $barang->nama_barang }}" 
                                                    data-harga="{{ $barang->harga_jual }}"
                                                    data-stok="{{ $barang->stok }}">
                                                {{ $barang->nama_barang }} (Stok: {{ $barang->stok }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-4">
                                    <h3 class="text-lg font-bold mb-2">Keranjang</h3>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full bg-white table-auto" id="cart-table">
                                            <thead class="bg-gray-200">
                                                <tr>
                                                    <th class="py-2 px-4 border-b text-left">Nama Barang</th>
                                                    <th class="py-2 px-4 border-b text-left w-28">Jumlah</th>
                                                    <th class="py-2 px-4 border-b text-left w-40">Harga Satuan</th>
                                                    <th class="py-2 px-4 border-b text-right w-40">Subtotal</th>
                                                    <th class="py-2 px-4 border-b text-center w-20">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!old('items'))
                                                    @foreach($transaksi->details as $item)
                                                    <tr data-id="{{ $item->barang_id }}">
                                                        <td class="py-2 px-4 border-b align-middle">{{ $item->barang->nama_barang }} @if ($item->barang->trashed()) <span class="text-xs text-red-500">(Dihapus)</span> @endif</td>
                                                        <td class="py-2 px-4 border-b align-middle">
                                                            <input type="number" class="jumlah-input w-full rounded-md border-gray-300" value="{{ $item->jumlah }}" min="1">
                                                        </td>
                                                        <td class="py-2 px-4 border-b align-middle">
                                                            <input type="text" class="harga-formatted w-full rounded-md border-gray-300" value="{{ number_format($item->harga_satuan, 0, ',', '.') }}" min="0">
                                                            <input type="hidden" class="harga-input" value="{{ $item->harga_satuan }}">
                                                        </td>
                                                        <td class="py-2 px-4 border-b font-bold subtotal text-right align-middle">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                                        <td class="py-2 px-4 border-b text-center align-middle">
                                                            <button type="button" class="remove-item-btn text-red-500 hover:text-red-700">X</button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="md:col-span-1">
                                <div class="bg-blue-100 p-4 rounded-lg shadow-md flex flex-col h-full">
                                    <h3 class="text-2xl font-bold mb-4">Total Belanja</h3>
                                    <div class="text-4xl font-extrabold text-blue-800 mb-6" id="grand-total">Rp 0</div>
                                    <input type="hidden" name="total_harga" id="total_harga_input" value="{{ old('total_harga', $transaksi->total_harga) }}">
                                    
                                    <div class="mb-4">
                                        <label for="uang_bayar_formatted" class="block font-medium text-sm text-gray-700">Uang Bayar</label>
                                        <input type="text" id="uang_bayar_formatted" inputmode="numeric" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ number_format(old('uang_bayar', $transaksi->uang_bayar), 0, ',', '.') }}" required>
                                        <input type="hidden" name="uang_bayar" id="uang_bayar" value="{{ old('uang_bayar', $transaksi->uang_bayar) }}">
                                    </div>

                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Uang Kembali</label>
                                        <div class="mt-1 p-2 bg-gray-200 rounded-md font-bold text-lg" id="uang-kembali">Rp 0</div>
                                    </div>

                                    <div class="mt-auto pt-6 border-t">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <button type="button" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm" onclick="if(confirm('Anda YAKIN ingin menghapus transaksi ini? Stok akan dikembalikan.')) document.getElementById('delete-form').submit();">
                                                    Hapus
                                                </button>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('transaksi.index') }}" class="text-gray-600 font-bold py-2 px-4 rounded text-sm hover:text-gray-900">Batal</a>
                                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                    Update
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <form action="{{ route('transaksi.destroy', $transaksi) }}" method="POST" id="delete-form" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>

                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#barang-search').select2({
                placeholder: "-- Cari Barang --"
            });

            const allProducts = @json($barangs->keyBy('id'));
            const oldItems = @json(old('items'));
            const cartTableBody = $('#cart-table tbody');
            const grandTotalEl = $('#grand-total');
            const totalHargaInput = $('#total_harga_input');
            const uangBayarInput = $('#uang_bayar');
            const uangBayarFormattedInput = $('#uang_bayar_formatted');
            const uangKembaliEl = $('#uang-kembali');
            const transactionForm = $('#transaction-form');
            let cartItems = {};

            $('#cart-table tbody tr').each(function() {
                const row = $(this);
                const id = row.data('id');
                cartItems[id] = { row: row };
            });

            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
            }

            $('#barang-search').on('select2:select', function (e) {
                const selectedOption = $(e.params.data.element);
                if (!selectedOption.val()) return;
                const barangId = selectedOption.val();
                const nama = selectedOption.data('nama');
                const harga = parseFloat(selectedOption.data('harga'));
                const stok = parseInt(selectedOption.data('stok'));
                addItemToCart(barangId, nama, harga, stok, 1);
                $(this).val(null).trigger('change');
            });

            function addItemToCart(id, nama, harga, stok, jumlah = 1) {
                if(stok <= 0 && !cartItems[id]) {
                    alert('Stok barang ' + nama + ' habis!');
                    return;
                }
                if (cartItems[id]) {
                    cartItems[id].row.find('.jumlah-input').focus();
                    return;
                }
                const newRow = $(`
                    <tr data-id="${id}">
                        <td class="py-2 px-4 border-b align-middle">${nama}</td>
                        <td class="py-2 px-4 border-b align-middle"><input type="number" class="jumlah-input w-full rounded-md border-gray-300" value="${jumlah}" min="1" max="${stok}" data-stok="${stok}"></td>
                        <td class="py-2 px-4 border-b align-middle">
                            <input type="text" class="harga-formatted w-full rounded-md border-gray-300" value="${harga.toLocaleString('id-ID')}" min="0">
                            <input type="hidden" class="harga-input" value="${harga}">
                        </td>
                        <td class="py-2 px-4 border-b font-bold subtotal text-right align-middle">${formatRupiah(harga * jumlah)}</td>
                        <td class="py-2 px-4 border-b text-center align-middle"><button type="button" class="remove-item-btn text-red-500 hover:text-red-700">X</button></td>
                    </tr>
                `);
                cartTableBody.append(newRow);
                cartItems[id] = { row: newRow };
                updateTotals();
            }

            function repopulateCart() {
                if (oldItems && oldItems.length > 0) {
                    cartTableBody.empty();
                    cartItems = {};
                    oldItems.forEach(item => {
                        const product = allProducts[item.barang_id];
                        if (product) {
                            const originalDetail = @json($transaksi->details->keyBy('barang_id'));
                            let currentStok = product.stok;
                            if(originalDetail[item.barang_id]) {
                                currentStok += originalDetail[item.barang_id].jumlah;
                            }
                            addItemToCart(
                                product.id, product.nama_barang,
                                parseFloat(item.harga_saat_transaksi), currentStok,
                                parseInt(item.jumlah)
                            );
                        }
                    });
                }
                updateTotals();
            }
            repopulateCart();

            cartTableBody.on('input', '.harga-formatted', function() {
                let rawValue = $(this).val().replace(/[^0-9]/g, '');
                $(this).siblings('.harga-input').val(rawValue);
                if (rawValue) {
                    $(this).val(parseInt(rawValue, 10).toLocaleString('id-ID'));
                } else {
                    $(this).val('');
                }
                updateRowSubtotal($(this).closest('tr'));
            });
            
            uangBayarFormattedInput.on('input', function() {
                let rawValue = $(this).val().replace(/[^0-9]/g, '');
                uangBayarInput.val(rawValue);
                if (rawValue) {
                    $(this).val(parseInt(rawValue, 10).toLocaleString('id-ID'));
                } else {
                    $(this).val('');
                }
                updateUangKembali();
            });

            cartTableBody.on('input', '.jumlah-input', function() {
                const row = $(this).closest('tr');
                const jumlah = parseInt(row.find('.jumlah-input').val());
                const stok = parseInt(row.find('.jumlah-input').data('stok'));
                if(jumlah > stok) {
                    alert('Jumlah pembelian melebihi stok yang tersedia (' + stok + ')');
                    row.find('.jumlah-input').val(stok);
                }
                updateRowSubtotal(row);
            });

            cartTableBody.on('click', '.remove-item-btn', function() {
                const row = $(this).closest('tr');
                delete cartItems[row.data('id')];
                row.remove();
                updateTotals();
            });

            function updateRowSubtotal(row) {
                const jumlah = parseFloat(row.find('.jumlah-input').val()) || 0;
                const harga = parseFloat(row.find('.harga-input').val()) || 0;
                row.find('.subtotal').text(formatRupiah(jumlah * harga));
                updateTotals();
            }

            function updateTotals() {
                let grandTotal = 0;
                cartTableBody.find('tr').each(function() {
                    const row = $(this);
                    const jumlah = parseFloat(row.find('.jumlah-input').val()) || 0;
                    const harga = parseFloat(row.find('.harga-input').val()) || 0;
                    grandTotal += jumlah * harga;
                });
                grandTotalEl.text(formatRupiah(grandTotal));
                totalHargaInput.val(grandTotal);
                updateUangKembali();
            }
            
            function updateUangKembali() {
                const total = parseFloat(totalHargaInput.val()) || 0;
                const bayar = parseFloat(uangBayarInput.val()) || 0;
                const kembali = bayar - total;
                uangKembaliEl.text(formatRupiah(kembali < 0 ? 0 : kembali));
            }
            
            transactionForm.on('submit', function (e) {
                $('input[name^="items["]').remove();
                let index = 0;
                cartTableBody.find('tr').each(function() {
                    const row = $(this);
                    const barangId = row.data('id');
                    const jumlah = row.find('.jumlah-input').val();
                    const harga = row.find('.harga-input').val();
                    
                    transactionForm.append(createHiddenInput(`items[${index}][barang_id]`, barangId));
                    transactionForm.append(createHiddenInput(`items[${index}][jumlah]`, jumlah));
                    transactionForm.append(createHiddenInput(`items[${index}][harga_saat_transaksi]`, harga));
                    index++;
                });
            });

            function createHiddenInput(name, value) {
                return $('<input>').attr({ type: 'hidden', name: name, value: value });
            }
        });
    </script>
    @endpush
</x-app-layout>