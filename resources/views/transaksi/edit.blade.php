<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('transaksi.index') }}" class="hover:underline">
                Riwayat Transaksi
            </a>
            <span class="mx-2 font-sans">&gt;</span>
            <span>
                Edit Transaksi
            </span>
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Pesan error tidak berubah --}}
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
                                    <select id="barang-search" class="block w-full rounded-md shadow-sm border-gray-300">
                                        <option value="">-- Pilih Barang --</option>
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
                                        <table class="min-w-full bg-white" id="cart-table">
                                            <thead class="bg-gray-200">
                                                <tr>
                                                    <th class="py-2 px-4 border-b text-left">Nama Barang</th>
                                                    <th class="py-2 px-4 border-b text-left w-24">Jumlah</th>
                                                    <th class="py-2 px-4 border-b text-left w-36">Harga Satuan</th>
                                                    <th class="py-2 px-4 border-b text-right w-36">Subtotal</th>
                                                    <th class="py-2 px-4 border-b text-center w-16">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($transaksi->details as $item)
                                                <tr data-id="{{ $item->barang_id }}">
                                                    <td class="py-2 px-4 border-b align-middle">{{ $item->barang->nama_barang }}</td>
                                                    <td class="py-2 px-4 border-b align-middle">
                                                        <input type="number" class="jumlah-input w-full rounded-md border-gray-300" value="{{ $item->jumlah }}" min="1">
                                                    </td>
                                                    <td class="py-2 px-4 border-b align-middle">
                                                        <input type="number" class="harga-input w-full rounded-md border-gray-300" value="{{ $item->harga_satuan }}" min="0">
                                                    </td>
                                                    <td class="py-2 px-4 border-b font-bold subtotal text-right align-middle">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                                    <td class="py-2 px-4 border-b text-center align-middle">
                                                        <button type="button" class="remove-item-btn text-red-500 hover:text-red-700">X</button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Panel Kanan tidak berubah --}}
                            <div class="md:col-span-1">
                                <div class="bg-blue-100 p-4 rounded-lg shadow-md">
                                    <h3 class="text-2xl font-bold mb-4">Total Belanja</h3>
                                    <div class="text-4xl font-extrabold text-blue-800 mb-6" id="grand-total">Rp 0</div>
                                    <input type="hidden" name="total_harga" id="total_harga_input" value="{{ $transaksi->total_harga }}">
                                    <div>
                                        <label for="uang_bayar" class="block font-medium text-sm text-gray-700">Uang Bayar</label>
                                        <input type="number" name="uang_bayar" id="uang_bayar" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ $transaksi->uang_bayar }}" required>
                                    </div>
                                    <div class="mt-4">
                                        <label class="block font-medium text-sm text-gray-700">Uang Kembali</label>
                                        <div class="mt-1 p-2 bg-gray-200 rounded-md font-bold text-lg" id="uang-kembali">Rp 0</div>
                                    </div>
                                    <div class="mt-6">
                                        <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded text-lg">
                                            Update Transaksi
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Sisa script tidak berubah
        document.addEventListener('DOMContentLoaded', function () {
            const barangSearch = document.getElementById('barang-search');
            const cartTableBody = document.querySelector('#cart-table tbody');
            const grandTotalEl = document.getElementById('grand-total');
            const totalHargaInput = document.getElementById('total_harga_input');
            const uangBayarInput = document.getElementById('uang_bayar');
            const uangKembaliEl = document.getElementById('uang-kembali');
            const transactionForm = document.getElementById('transaction-form');
            let cartItems = {};

            document.querySelectorAll('#cart-table tbody tr').forEach(row => {
                const id = row.dataset.id;
                cartItems[id] = { row: row };
            });

            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
            }

            barangSearch.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                if (!selectedOption.value) return;
                const barangId = selectedOption.value;
                const nama = selectedOption.dataset.nama;
                const harga = parseFloat(selectedOption.dataset.harga);
                const stok = parseInt(selectedOption.dataset.stok);
                addItemToCart(barangId, nama, harga, stok);
                this.value = '';
            });

            function addItemToCart(id, nama, harga, stok) {
                if(stok <= 0) {
                    alert('Stok barang ' + nama + ' habis!');
                    return;
                }
                if (cartItems[id]) {
                    cartItems[id].row.querySelector('.jumlah-input').focus();
                    return;
                }
                const newRow = document.createElement('tr');
                newRow.dataset.id = id;
                newRow.innerHTML = `
                    <td class="py-2 px-4 border-b">${nama}</td>
                    <td class="py-2 px-4 border-b"><input type="number" class="jumlah-input w-full rounded-md border-gray-300" value="1" min="1" max="${stok}"></td>
                    <td class="py-2 px-4 border-b"><input type="number" class="harga-input w-full rounded-md border-gray-300" value="${harga}" min="0"></td>
                    <td class="py-2 px-4 border-b font-bold subtotal">${formatRupiah(harga)}</td>
                    <td class="py-2 px-4 border-b text-center"><button type="button" class="remove-item-btn text-red-500 hover:text-red-700">X</button></td>
                `;
                cartTableBody.appendChild(newRow);
                cartItems[id] = { row: newRow };
                updateTotals();
            }

            cartTableBody.addEventListener('input', e => {
                if (e.target.classList.contains('jumlah-input') || e.target.classList.contains('harga-input')) {
                    updateRowSubtotal(e.target.closest('tr'));
                }
            });

            cartTableBody.addEventListener('click', e => {
                if (e.target.classList.contains('remove-item-btn')) {
                    const row = e.target.closest('tr');
                    delete cartItems[row.dataset.id];
                    row.remove();
                    updateTotals();
                }
            });

            function updateRowSubtotal(row) {
                const jumlah = parseFloat(row.querySelector('.jumlah-input').value) || 0;
                const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
                row.querySelector('.subtotal').textContent = formatRupiah(jumlah * harga);
                updateTotals();
            }

            function updateTotals() {
                let grandTotal = 0;
                cartTableBody.querySelectorAll('tr').forEach(row => {
                    const jumlah = parseFloat(row.querySelector('.jumlah-input').value) || 0;
                    const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
                    grandTotal += jumlah * harga;
                });
                grandTotalEl.textContent = formatRupiah(grandTotal);
                totalHargaInput.value = grandTotal;
                updateUangKembali();
            }
            
            uangBayarInput.addEventListener('input', updateUangKembali);

            function updateUangKembali() {
                const total = parseFloat(totalHargaInput.value) || 0;
                const bayar = parseFloat(uangBayarInput.value) || 0;
                const kembali = bayar - total;
                uangKembaliEl.textContent = formatRupiah(kembali < 0 ? 0 : kembali);
            }
            
            transactionForm.addEventListener('submit', function (e) {
                document.querySelectorAll('input[name^="items["]').forEach(el => el.remove());
                let index = 0;
                cartTableBody.querySelectorAll('tr').forEach(row => {
                    const barangId = row.dataset.id;
                    const jumlah = row.querySelector('.jumlah-input').value;
                    const harga = row.querySelector('.harga-input').value;
                    transactionForm.appendChild(createHiddenInput(`items[${index}][barang_id]`, barangId));
                    transactionForm.appendChild(createHiddenInput(`items[${index}][jumlah]`, jumlah));
                    transactionForm.appendChild(createHiddenInput(`items[${index}][harga_saat_transaksi]`, harga));
                    index++;
                });
            });

            function createHiddenInput(name, value) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = value;
                return input;
            }
            updateTotals();
        });
    </script>
</x-app-layout>