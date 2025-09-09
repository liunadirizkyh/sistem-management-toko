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

            <div id="filter-overlay" class="fixed inset-0 bg-black bg-opacity-50 opacity-0 hidden transition-opacity duration-300 ease-in-out z-40"></div>

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

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 flex items-center gap-4">
                    <div class="bg-blue-100 text-blue-600 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 truncate">Total Pendapatan</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900">Rp {{ number_format($pendapatanTotal, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 flex items-center gap-4">
                    <div class="bg-green-100 text-green-600 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 truncate">Jumlah Transaksi</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($jumlahTransaksi) }}</p>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 flex items-center gap-4">
                    <div class="bg-red-100 text-red-600 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 truncate">Total Hutang Supplier</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900">Rp {{ number_format($totalHutang, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <p class="text-xs font-medium text-gray-400">Detail Pendapatan</p>
                    <p class="text-sm font-medium text-gray-500 truncate mt-2">Cash</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">Rp {{ number_format($pendapatanCash, 0, ',', '.') }}</p>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <p class="text-xs font-medium text-gray-400">Detail Pendapatan</p>
                    <p class="text-sm font-medium text-gray-500 truncate mt-2">Transfer</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">Rp {{ number_format($pendapatanTransfer, 0, ',', '.') }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-3">Top Pelanggan</h3>
                    <div class="space-y-3">
                        @forelse ($topPelanggan as $index => $pelanggan)
                            <div class="flex items-center text-sm">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-700 truncate">{{ $pelanggan->nama_pelanggan }}</p>
                                </div>
                                <div class="text-gray-600 font-semibold">
                                    Rp {{ number_format($pelanggan->total_pembelian, 0, ',', '.') }}
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada data pelanggan.</p>
                        @endforelse
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
                const filterOverlay = document.getElementById('filter-overlay');
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
                    filterOverlay.classList.remove('hidden');
                    setTimeout(() => {
                        filterOverlay.classList.add('opacity-50');
                    }, 10);
                });

                function closeFilter() {
                    filterBox.classList.add('translate-x-full');
                    filterOverlay.classList.remove('opacity-50');
                    setTimeout(() => {
                        filterOverlay.classList.add('hidden');
                    }, 300);
                }
                
                filterClose.addEventListener('click', closeFilter);
                filterOverlay.addEventListener('click', closeFilter);


                toggleSelectors();
                filterTypeRadios.forEach(radio => {
                    radio.addEventListener('change', toggleSelectors);
                });
            });
        </script>
    @endpush
</x-app-layout>