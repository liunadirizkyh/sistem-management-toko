<x-app-layout>
    <x-slot name="header">
        <div class="relative flex items-center h-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <a href="{{ route('transaksi.index') }}" class="hover:underline">
                    Riwayat Transaksi
                </a>
                <span class="mx-2 font-sans">&gt;</span>
                <span>
                    Detail
                </span>
            </h2>
            
            <div class="absolute top-0 right-0 h-full flex items-center no-print">
                <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Nota
                </button>
            </div>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div id="nota" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold">Sumber Rezeki</h1>
                    <p class="text-gray-600">Jl. Mohnoh Nur No.204, Leuwimekar, Kec. Leuwiliang, Kabupaten Bogor</p>
                    <p class="text-gray-600">Telp: 0812-3456-7890</p>
                </div>

                <div class="border-b pb-4 mb-4 text-sm space-y-1">
                    <p><strong>No. Transaksi:</strong> {{ $transaksi->nomor_transaksi }}</p>
                    <div class="flex justify-between">
                        <p><strong>Tanggal:</strong> {{ $transaksi->created_at->format('d M Y, H:i:s') }}</p>
                        <p><strong>Pelanggan:</strong> {{ $transaksi->nama_pelanggan ?? 'Umum' }}</p>
                    </div>
                </div>

                <table class="w-full mb-6 text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2 text-left font-semibold">Nama Barang</th>
                            <th class="py-2 text-center font-semibold">Jumlah</th>
                            <th class="py-2 text-right font-semibold">Harga Satuan</th>
                            <th class="py-2 text-right font-semibold">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksi->details as $item)
                        <tr class="border-b">
                            <td class="py-2">{{ $item->barang->nama_barang }} @if ($item->barang->trashed()) <span class="text-xs text-red-500">(Dihapus)</span> @endif</td>
                            <td class="py-2 text-center">{{ $item->jumlah }} {{ $item->barang->satuan }}</td>
                            <td class="py-2 text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td class="py-2 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="flex justify-end">
                    <div class="w-full sm:w-1/2 text-right">
                        <div class="flex justify-between mb-1">
                            <span class="font-semibold">Total Belanja:</span>
                            <span class="font-bold text-lg">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between mb-1 text-gray-600">
                            <span>Bayar:</span>
                            <span>Rp {{ number_format($transaksi->uang_bayar, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between pt-1 border-t">
                            <span class="font-semibold">Kembali:</span>
                            <span class="font-semibold">Rp {{ number_format($transaksi->uang_kembali, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-10 pt-4 border-t text-sm text-gray-500">
                    <p>Terima kasih telah berbelanja!</p>
                    <p>Barang yang sudah dibeli tidak dapat dikembalikan.</p>
                </div>
                
            </div>
        </div>
    </div>
    
    <style>
        @media print {
            body {
                background-color: #fff;
            }
            .no-print {
                display: none !important;
            }
            .py-12 {
                padding: 0;
            }
            .max-w-3xl {
                max-width: 100%;
                padding: 0;
                margin: 0;
            }
            #nota {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none;
                border: none;
                margin: 0;
                padding: 0;
            }
        }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('header').classList.add('no-print');
        });
    </script>
</x-app-layout>