<x-app-layout>
    <x-slot name="header">
        <div class="relative h-full flex items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>

            <div class="absolute top-0 right-0 h-full flex items-center">
                <button id="filter-toggle"
                    class="flex items-center gap-2 bg-white hover:bg-gray-100 text-black text-sm font-medium px-4 py-2 rounded-lg border border-gray-300 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-black" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 13.414V19a1 1 0 01-1.447.894l-4-2A1 1 0 018 17v-3.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    Filter
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative">

            <div id="filter-box"
                class="fixed top-0 right-0 h-full w-full md:w-[28rem] bg-white shadow-2xl border-l border-gray-200 transform translate-x-full transition-transform duration-300 ease-in-out z-50">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Filter Laporan</h3>
                    <button id="filter-close"
                        class="text-gray-400 hover:text-gray-600 transition rounded-full p-2">
                        ✕
                    </button>
                </div>
                <div class="p-6 overflow-y-auto h-full pb-20">
                    <form action="{{ route('dashboard') }}" method="GET" class="space-y-6">
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-2">Tipe Laporan</label>
                            <div class="flex rounded-md shadow-sm overflow-hidden">
                                <input type="radio" name="filter_type" id="daily" value="daily"
                                    class="sr-only peer/daily"
                                    {{ $selectedFilterType == 'daily' ? 'checked' : '' }}>
                                <label for="daily"
                                    class="flex-1 text-center px-4 py-2 text-sm cursor-pointer border border-gray-300 font-medium text-gray-600 hover:bg-gray-50 peer-checked/daily:bg-blue-500 peer-checked/daily:text-white peer-checked/daily:border-blue-500">
                                    Harian
                                </label>

                                <input type="radio" name="filter_type" id="monthly" value="monthly"
                                    class="sr-only peer/monthly"
                                    {{ $selectedFilterType == 'monthly' ? 'checked' : '' }}>
                                <label for="monthly"
                                    class="flex-1 text-center px-4 py-2 text-sm cursor-pointer border border-gray-300 font-medium text-gray-600 hover:bg-gray-50 peer-checked/monthly:bg-blue-500 peer-checked/monthly:text-white peer-checked/monthly:border-blue-500">
                                    Bulanan
                                </label>

                                <input type="radio" name="filter_type" id="yearly" value="yearly"
                                    class="sr-only peer/yearly"
                                    {{ $selectedFilterType == 'yearly' ? 'checked' : '' }}>
                                <label for="yearly"
                                    class="flex-1 text-center px-4 py-2 text-sm cursor-pointer border border-gray-300 font-medium text-gray-600 hover:bg-gray-50 peer-checked/yearly:bg-blue-500 peer-checked/yearly:text-white peer-checked/yearly:border-blue-500">
                                    Tahunan
                                </label>
                            </div>
                        </div>

                        <div id="day-selector">
                            <label for="day" class="block font-medium text-sm text-gray-700">Tanggal</label>
                            <select name="day" id="day"
                                class="mt-1 w-full rounded-md shadow-sm border-gray-300">
                                @for ($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}"
                                        {{ $selectedDay == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div id="month-selector">
                            <label for="month" class="block font-medium text-sm text-gray-700">Bulan</label>
                            <select name="month" id="month"
                                class="mt-1 w-full rounded-md shadow-sm border-gray-300">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}"
                                        {{ $selectedMonth == $i ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label for="year" class="block font-medium text-sm text-gray-700">Tahun</label>
                            <select name="year" id="year"
                                class="mt-1 w-full rounded-md shadow-sm border-gray-300">
                                @for ($i = now()->year; $i >= 2020; $i--)
                                    <option value="{{ $i }}"
                                        {{ $selectedYear == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="pt-4">
                            <button type="submit"
                                class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600 transition">
                                Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Laporan Penjualan</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <p class="text-sm font-medium text-gray-500 truncate">Pendapatan</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">
                            Rp {{ number_format($pendapatan, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <p class="text-sm font-medium text-gray-500 truncate">Jumlah Transaksi</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">
                            {{ number_format($jumlahTransaksi) }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <p class="text-sm font-medium text-gray-500 truncate">Barang Terjual</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">
                            {{ number_format($barangTerjual) }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Laporan Hutang & Pembayaran</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <p class="text-sm font-medium text-gray-500 truncate">Hutang Baru (Belum Lunas)</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">
                            Rp {{ number_format($totalHutang, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <p class="text-sm font-medium text-gray-500 truncate">Hutang Nyicil Baru</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">
                            Rp {{ number_format($sisaHutangNyicil, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <p class="text-sm font-medium text-gray-500 truncate">Hutang Terbayar</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">
                            Rp {{ number_format($hutangDilunasiPeriodeIni, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const filterToggle = document.getElementById('filter-toggle');
                const filterClose = document.getElementById('filter-close');
                const filterBox = document.getElementById('filter-box');
                const filterTypeRadios = document.querySelectorAll('input[name="filter_type"]');
                const daySelector = document.getElementById('day-selector');
                const monthSelector = document.getElementById('month-selector');

                function toggleSelectors() {
                    const selectedType = document.querySelector('input[name="filter_type"]:checked')?.value;
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

                filterToggle.addEventListener('click', () => {
                    filterBox.classList.remove('translate-x-full');
                });

                filterClose.addEventListener('click', () => {
                    filterBox.classList.add('translate-x-full');
                });

                toggleSelectors();
                filterTypeRadios.forEach(radio => {
                    radio.addEventListener('change', toggleSelectors);
});
            });
        </script>
    @endpush
</x-app-layout>