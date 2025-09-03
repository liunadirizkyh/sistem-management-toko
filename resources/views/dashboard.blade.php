<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Form Filter -->
            <div class="mb-6 p-6 bg-white rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Laporan</h3>
                <form action="{{ route('dashboard') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-10 gap-4 items-end">
                        
                        <div class="md:col-span-3">
                            <label class="block font-medium text-sm text-gray-700 mb-1">Tipe Laporan</label>
                            <div class="flex rounded-md shadow-sm">
                                <div class="relative flex-1">
                                    <input type="radio" name="filter_type" id="daily" value="daily" class="sr-only peer" {{ $selectedFilterType == 'daily' ? 'checked' : '' }}>
                                    <label for="daily" class="block w-full text-center px-4 py-2 rounded-l-md border border-gray-300 cursor-pointer text-sm font-medium text-gray-600 peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500 hover:bg-gray-50">Harian</label>
                                </div>
                                <div class="relative flex-1">
                                    <input type="radio" name="filter_type" id="monthly" value="monthly" class="sr-only peer" {{ $selectedFilterType == 'monthly' ? 'checked' : '' }}>
                                    <label for="monthly" class="block w-full text-center px-4 py-2 border-t border-b border-gray-300 cursor-pointer text-sm font-medium text-gray-600 peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500 hover:bg-gray-50">Bulanan</label>
                                </div>
                                <div class="relative flex-1">
                                    <input type="radio" name="filter_type" id="yearly" value="yearly" class="sr-only peer" {{ $selectedFilterType == 'yearly' ? 'checked' : '' }}>
                                    <label for="yearly" class="block w-full text-center px-4 py-2 rounded-r-md border border-gray-300 cursor-pointer text-sm font-medium text-gray-600 peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500 hover:bg-gray-50">Tahunan</label>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2" id="day-selector">
                            <label for="day" class="block font-medium text-sm text-gray-700">Tanggal</label>
                            <select name="day" id="day" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
                                @for ($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}" {{ $selectedDay == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="md:col-span-2" id="month-selector">
                            <label for="month" class="block font-medium text-sm text-gray-700">Bulan</label>
                            <select name="month" id="month" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $selectedMonth == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label for="year" class="block font-medium text-sm text-gray-700">Tahun</label>
                            <select name="year" id="year" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
                                @for ($i = now()->year; $i >= 2020; $i--)
                                    <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block font-medium text-sm text-transparent hidden md:block">.</label>
                            <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-700">
                                Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Laporan Penjualan</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <p class="text-sm font-medium text-gray-500 truncate">Pendapatan</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">Rp {{ number_format($pendapatan, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <p class="text-sm font-medium text-gray-500 truncate">Jumlah Transaksi</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($jumlahTransaksi) }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <p class="text-sm font-medium text-gray-500 truncate">Barang Terjual</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($barangTerjual) }}</p>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Laporan Hutang & Pembayaran</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <p class="text-sm font-medium text-gray-500 truncate">Hutang Baru (Belum Lunas)</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">Rp {{ number_format($totalHutang, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <p class="text-sm font-medium text-gray-500 truncate">Hutang Nyicil Baru</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">Rp {{ number_format($sisaHutangNyicil, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <p class="text-sm font-medium text-gray-500 truncate">Hutang Terbayar</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">Rp {{ number_format($hutangDilunasiPeriodeIni, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterTypeRadios = document.querySelectorAll('input[name="filter_type"]');
            const daySelector = document.getElementById('day-selector');
            const monthSelector = document.getElementById('month-selector');

            function toggleSelectors() {
                const selectedType = document.querySelector('input[name="filter_type"]:checked').value;
                if (selectedType === 'daily') {
                    daySelector.style.display = 'block';
                    monthSelector.style.display = 'block';
                } else if (selectedType === 'monthly') {
                    daySelector.style.display = 'none';
                    monthSelector.style.display = 'block';
                } else if (selectedType === 'yearly') {
                    daySelector.style.display = 'none';
                    monthSelector.style.display = 'none';
                }
            }
            toggleSelectors();
            filterTypeRadios.forEach(radio => {
                radio.addEventListener('change', toggleSelectors);
            });
        });
    </script>
    @endpush
</x-app-layout>