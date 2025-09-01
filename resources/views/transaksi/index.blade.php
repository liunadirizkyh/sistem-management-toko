<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Daftar Semua Transaksi</h3>
                        <a href="{{ route('transaksi.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Buat Transaksi Baru
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white table-auto">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">No. Transaksi</th>
                                    <th class="py-2 px-4 border-b text-left">Tanggal</th>
                                    <th class="py-2 px-4 border-b text-right">Total Belanja</th>
                                    <th class="py-2 px-4 border-b text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaksis as $transaksi)
                                    <tr class="hover:bg-gray-100">
                                        <td class="py-2 px-4 border-b align-middle font-mono text-sm">{{ $transaksi->nomor_transaksi }}</td>
                                        <td class="py-2 px-4 border-b align-middle">{{ $transaksi->created_at->format('d M Y, H:i') }}</td>
                                        <td class="py-2 px-4 border-b align-middle text-right">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                                        <td class="py-2 px-4 border-b align-middle">
                                            <div class="flex items-center justify-center space-x-3">
                                                <a href="{{ route('transaksi.show', $transaksi) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                                    Detail
                                                </a>
                                                @role('admin')
                                                    <a href="{{ route('transaksi.edit', $transaksi) }}" class="text-yellow-600 hover:text-yellow-900 font-semibold">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('transaksi.destroy', $transaksi) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus transaksi ini? Stok barang akan dikembalikan.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @endrole
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-gray-500">
                                            Belum ada riwayat transaksi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $transaksis->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>