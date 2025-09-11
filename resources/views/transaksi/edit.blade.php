<x-app-layout>
    <x-slot name="header">
        <div class="relative flex items-center h-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <a href="{{ route('transaksi.index') }}" class="hover:underline">
                    Riwayat Transaksi
                </a>
                <span class="mx-2 font-sans">&gt;</span>
                <span>
                    @role('admin')
                        Edit Transaksi
                    @else
                        Detail Transaksi
                    @endrole
                </span>
            </h2>
            <div class="absolute top-0 right-0 h-full flex items-center">
                <button type="button" data-url="{{ route('transaksi.show', $transaksi) }}" class="print-nota-btn flex items-center gap-2 bg-white hover:bg-gray-100 text-gray-800 text-sm font-medium px-4 py-2 rounded-lg border border-gray-300 transition shadow-sm no-print">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Nota
                </button>
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
                        <fieldset @unlessrole('admin') disabled @endunlessrole>
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                <div class="lg:col-span-2 space-y-6">
                                    <div>
                                        <label for="barang-search" class="block font-medium text-sm text-gray-700 mb-1">Pilih Barang</label>
                                        <!-- Dropdown sekarang kosong, akan diisi oleh AJAX -->
                                        <select id="barang-search" class="w-full disabled:bg-gray-200 disabled:cursor-not-allowed"></select>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Keranjang</h3>
                                        <div class="overflow-y-auto h-[330px]">
                                            <table class="min-w-full bg-white" id="cart-table">
                                                <thead class="bg-gray-100 sticky top-0">
                                                    <tr>
                                                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Jumlah</th>
                                                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Harga</th>
                                                        <th class="py-2 px-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Subtotal</th>
                                                        <th class="py-2 px-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200">
                                                    @if(!old('items'))
                                                        @foreach($transaksi->details as $item)
                                                        <tr data-id="{{ $item->barang_id }}">
                                                            <td class="p-2 border-b align-middle">{{ $item->barang->nama_barang }} @if ($item->barang->trashed()) <span class="text-xs text-red-500">(Dihapus)</span> @endif</td>
                                                            <td class="p-2 border-b align-middle"><input type="number" class="jumlah-input w-full rounded-md border-gray-300 disabled:bg-gray-100" value="{{ $item->jumlah }}" min="1"></td>
                                                            <td class="p-2 border-b align-middle">
                                                                <input type="text" class="harga-formatted w-full rounded-md border-gray-300 disabled:bg-gray-100" value="{{ number_format($item->harga_satuan, 0, ',', '.') }}" min="0">
                                                                <input type="hidden" class="harga-input" value="{{ $item->harga_satuan }}">
                                                            </td>
                                                            <td class="p-2 border-b font-semibold subtotal text-right align-middle">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                                            <td class="p-2 border-b text-center align-middle"><button type="button" class="remove-item-btn text-red-500 hover:text-red-700 font-bold">X</button></td>
                                                        </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="lg:col-span-1">
                                <div class="bg-gray-50 p-6 rounded-lg shadow-md lg:sticky lg:top-8 border">
                                        <h3 class="text-xl font-bold mb-4 text-gray-800">Ringkasan Belanja</h3>
                                        <div class="mb-4">
                                            <label for="nama_pelanggan" class="block font-medium text-sm text-gray-700">Nama Pelanggan</label>
                                            <input type="text" name="nama_pelanggan" id="nama_pelanggan" value="{{ old('nama_pelanggan', $transaksi->nama_pelanggan) }}" class="block mt-1 w-full rounded-lg shadow-sm border-gray-300 disabled:bg-gray-100" placeholder="Opsional" autocomplete="off">
                                        </div>
                                        <div class="mb-4">
                                            <label for="metode_pembayaran" class="block font-medium text-sm text-gray-700">Metode Pembayaran</label>
                                            <select name="metode_pembayaran" id="metode_pembayaran" class="block mt-1 w-full rounded-lg shadow-sm border-gray-300 disabled:bg-gray-100">
                                                <option value="cash" {{ old('metode_pembayaran', $transaksi->metode_pembayaran) == 'cash' ? 'selected' : '' }}>Cash</option>
                                                <option value="transfer" {{ old('metode_pembayaran', $transaksi->metode_pembayaran) == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                            </select>
                                        </div>
                                        <div class="relative min-h-[88px]">
                                            <div id="via-bank-container" class="mb-4 absolute w-full" style="display: none;">
                                                <label for="via_bank" class="block font-medium text-sm text-gray-700">Via Bank</label>
                                                <input type="text" name="via_bank" id="via_bank" class="block mt-1 w-full rounded-lg shadow-sm border-gray-300 disabled:bg-gray-100" value="{{ old('via_bank', $transaksi->via_bank) }}">
                                            </div>
                                            <div id="cash-payment-container" class="absolute w-full">
                                                <label for="uang_bayar_formatted" class="block font-medium text-sm text-gray-700">Uang Bayar</label>
                                                <input type="text" id="uang_bayar_formatted" inputmode="numeric" class="block mt-1 w-full rounded-lg shadow-sm border-gray-300 disabled:bg-gray-100" value="{{ number_format(old('uang_bayar', $transaksi->uang_bayar), 0, ',', '.') }}">
                                                <input type="hidden" name="uang_bayar" id="uang_bayar" value="{{ old('uang_bayar', $transaksi->uang_bayar) }}">
                                            </div>
                                        </div>
                                        <div class="mt-6 pt-4 border-t space-y-2">
                                            <div class="flex justify-between items-center text-gray-600">
                                                <span>Total</span>
                                                <span id="grand-total" class="font-semibold text-lg text-gray-800">Rp 0</span>
                                            </div>
                                            <div id="cash-return-container" class="flex justify-between items-center text-gray-600">
                                                <span>Kembali</span>
                                                <span id="uang-kembali" class="font-semibold text-gray-800">Rp 0</span>
                                            </div>
                                        </div>
                                        <input type="hidden" name="total_harga" id="total_harga_input" value="{{ old('total_harga', $transaksi->total_harga) }}">
                                        
                                        <div class="mt-auto pt-6">
                                            @role('admin')
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <button type="button" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg text-sm" onclick="if(confirm('Anda YAKIN? Stok akan dikembalikan.')) document.getElementById('delete-form').submit();">
                                                            Hapus
                                                        </button>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <a href="{{ route('transaksi.index') }}" class="hover:text-gray-900 text-gray-700 font-bold py-2 px-4 rounded-lg text-sm">Batal</a>
                                                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg text-sm" form="transaction-form">
                                                            Update
                                                        </button>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="flex items-center justify-end">
                                                    <a href="{{ route('transaksi.index') }}" class="w-full sm:w-auto text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg text-sm">
                                                        Kembali
                                                    </a>
                                                </div>
                                            @endrole
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
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
                placeholder: "-- Cari Nama atau Kode Barang --",
                ajax: {
                    url: '{{ route('barang.search') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { term: params.term };
                    },
                    processResults: function (data) {
                        return { results: data };
                    },
                    cache: true
                },
                @unlessrole('admin')
                disabled: true
                @endunlessrole
            });

            const oldItems = @json(old('items'));
            const cartTableBody = $('#cart-table tbody');
            const grandTotalEl = $('#grand-total');
            const totalHargaInput = $('#total_harga_input');
            const transactionForm = $('#transaction-form');
            let cartItems = {};

            const paymentMethodSelect = $('#metode_pembayaran');
            const cashPaymentContainer = $('#cash-payment-container');
            const viaBankContainer = $('#via-bank-container');
            const cashReturnContainer = $('#cash-return-container');
            const uangBayarInput = $('#uang_bayar');
            const uangBayarFormattedInput = $('#uang_bayar_formatted');
            const viaBankInput = $('#via_bank');
            const uangKembaliEl = $('#uang-kembali');

            $('#cart-table tbody tr').each(function() {
                const row = $(this);
                const id = row.data('id');
                cartItems[id] = { row: row };
            });

            function togglePaymentFields() {
                const selectedMethod = paymentMethodSelect.val();
                if (selectedMethod === 'transfer') {
                    cashPaymentContainer.hide();
                    viaBankContainer.show();
                    cashReturnContainer.css('visibility', 'hidden');
                    uangBayarFormattedInput.prop('required', false);
                    viaBankInput.prop('required', true);
                } else {
                    cashPaymentContainer.show();
                    viaBankContainer.hide();
                    cashReturnContainer.css('visibility', 'visible');
                    uangBayarFormattedInput.prop('required', true);
                    viaBankInput.prop('required', false);
                }
            }
            togglePaymentFields();
            paymentMethodSelect.on('change', togglePaymentFields);

            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
            }

            $('#barang-search').on('select2:select', function (e) {
                const data = e.params.data.data;
                const barangId = e.params.data.id;
                addItemToCart(barangId, data.nama, data.harga, data.stok, 1);
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
                        <td class="p-2 align-middle">${nama}</td>
                        <td class="p-2 align-middle"><input type="number" class="jumlah-input w-full rounded-md border-gray-300" value="${jumlah}" min="1" max="${stok}" data-stok="${stok}"></td>
                        <td class="p-2 align-middle">
                            <input type="text" class="harga-formatted w-full rounded-md border-gray-300" value="${harga.toLocaleString('id-ID')}" min="0">
                            <input type="hidden" class="harga-input" value="${harga}">
                        </td>
                        <td class="p-2 font-semibold subtotal text-right align-middle">${formatRupiah(harga * jumlah)}</td>
                        <td class="p-2 text-center align-middle"><button type="button" class="remove-item-btn text-red-500 hover:text-red-700 font-bold">X</button></td>
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
                    
                    const existingProducts = @json($transaksi->details->mapWithKeys(function ($detail) {
                        return [$detail->barang_id => [
                            'nama_barang' => $detail->barang->nama_barang,
                            'stok' => $detail->barang->stok + $detail->jumlah
                        ]];
                    }));

                    oldItems.forEach(item => {
                        const product = existingProducts[item.barang_id];
                        if (product) {
                            addItemToCart(
                                item.barang_id,
                                product.nama_barang,
                                parseFloat(item.harga_saat_transaksi),
                                product.stok,
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
                let totalJenis = 0;
                let totalKuantitas = 0;
                cartTableBody.find('tr').each(function() {
                    const row = $(this);
                    const jumlah = parseFloat(row.find('.jumlah-input').val()) || 0;
                    const harga = parseFloat(row.find('.harga-input').val()) || 0;
                    grandTotal += jumlah * harga;
                    totalKuantitas += jumlah;
                    totalJenis++;
                });
                grandTotalEl.text(formatRupiah(grandTotal));
                totalHargaInput.val(grandTotal);
                $('#total-jenis-barang').text(totalJenis);
                $('#total-kuantitas-barang').text(totalKuantitas);
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

            $('.print-nota-btn').on('click', function(e) {
                e.preventDefault();
                const url = $(this).data('url');
                $('iframe[name="nota-frame"]').remove();
                const iframe = $('<iframe>', {
                    name: 'nota-frame',
                    src: url,
                    style: 'display:none;'
                }).appendTo('body');
                iframe.on('load', function() {
                    try {
                        this.contentWindow.focus();
                        this.contentWindow.print();
                    } catch (e) {
                        console.error("Gagal print:", e);
                    }
                    setTimeout(() => $(this).remove(), 1000);
                });
            });
        });
    </script>
    @endpush
</x-app-layout>