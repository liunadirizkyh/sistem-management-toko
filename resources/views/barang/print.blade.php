<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Stok Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4;
            margin: 1.5cm;
        }
        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            word-wrap: break-word;
        }
        thead {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="text-center mb-6">
        <h1 class="text-xl font-bold">Daftar Stok Barang</h1>
        <p class="text-xs text-gray-600">Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }}</p>
    </div>

    <table class="text-xs">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Kode Barang</th>
                <th class="text-center">Stok</th>
                <th>Lokasi Barang</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barangs as $barang)
                <tr>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ optional($barang->kodeBarang)->kode ?? '-' }}</td>
                    <td class="text-center">{{ $barang->stok }} {{ $barang->satuan }}</td>
                    <td>{{ $barang->lokasi_barang ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data untuk ditampilkan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

