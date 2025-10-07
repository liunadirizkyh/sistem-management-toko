<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('piutang.index') }}" class="hover:underline">Piutang Pelanggan</a>
            <span class="mx-2 font-sans">&gt;</span>
            <span>Detail: {{ $pelanggan->nama_pelanggan }}</span>
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 p-4 rounded-lg @if($pelanggan->saldo > 0) bg-red-100 @elseif($pelanggan->saldo < 0) bg-green-100 @else bg-gray-100 @endif">
                        <h3 class="font-bold text-lg">
                            Sisa Piutang / Deposit: 
                            <span class="font-mono @if($pelanggan->saldo > 0) text-red-600 @elseif($pelanggan->saldo < 0) text-green-600 @endif">
                                Rp {{ number_format(abs($pelanggan->saldo), 0, ',', '.') }}
                                <span class="text-sm">{{ $pelanggan->saldo > 0 ? '(Hutang)' : ($pelanggan->saldo < 0 ? '(Deposit)' : '') }}</span>
                            </span>
                        </h3>
                    </div>
                    <h4 class="font-bold mb-2">Riwayat Transaksi:</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200 text-xs text-gray-600 uppercase">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">Tanggal</th>
                                    <th class="py-2 px-4 border-b text-left">Deskripsi</th>
                                    <th class="py-2 px-4 border-b text-right">Pengambilan (Debit)</th>
                                    <th class="py-2 px-4 border-b text-right">Pembayaran (Kredit)</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @foreach($piutangs as $piutang)
                                    <tr>
                                        <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($piutang->tanggal)->format('d M Y') }}</td>
                                        <td class="py-2 px-4 border-b">{{ $piutang->deskripsi }}</td>
                                        <td class="py-2 px-4 border-b text-right font-mono text-red-600">
                                            @if($piutang->tipe == 'pengambilan')
                                                Rp {{ number_format($piutang->jumlah, 0, ',', '.') }}
                                            @else - @endif
                                        </td>
                                        <td class="py-2 px-4 border-b text-right font-mono text-green-600">
                                            @if($piutang->tipe == 'pembayaran')
                                                Rp {{ number_format($piutang->jumlah, 0, ',', '.') }}
                                            @else - @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {{ $piutangs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
