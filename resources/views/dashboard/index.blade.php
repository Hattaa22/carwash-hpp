@extends('layouts.app')

@section('title', 'Dashboard - Sistem HPP Car Wash')

@section('content')
<div class="px-4 sm:px-0">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600 mt-2">Analisis HPP dan performa layanan car wash</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200 hover:shadow-xl transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <dt class="text-sm font-medium text-gray-500">Total HPP Entries</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ $totalEntries ?? 0 }}</dd>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200 hover:shadow-xl transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <dt class="text-sm font-medium text-gray-500">Rata-rata HPP</dt>
                        <dd class="text-2xl font-bold text-gray-900">Rp {{ number_format($avgHpp ?? 0, 0, ',', '.') }}</dd>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200 hover:shadow-xl transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <dt class="text-sm font-medium text-gray-500">Margin Tertinggi</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ number_format($maxMargin ?? 0, 1) }}%</dd>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200 hover:shadow-xl transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1M8 7l8 8m0-8v8a2 2 0 01-2 2H6a2 2 0 01-2-2V9a2 2 0 012-2h8a2 2 0 012 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <dt class="text-sm font-medium text-gray-500">Layanan Terpopuler</dt>
                        <dd class="text-lg font-bold text-gray-900">{{ $popularService ?? '-' }}</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- HPP vs Margin Chart -->
        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">HPP vs Margin Analysis</h3>
            <div class="h-64">
                <canvas id="hppMarginChart"></canvas>
            </div>
        </div>

        <!-- Vehicle Type Distribution -->
        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Jenis Kendaraan</h3>
            <div class="h-64">
                <canvas id="vehicleTypeChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Data</h3>
        <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4" x-data="{ loading: false }" @submit="loading = true">
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="jenis_kendaraan" class="block text-sm font-medium text-gray-700">Jenis Kendaraan</label>
                <select id="jenis_kendaraan" name="jenis_kendaraan" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua</option>
                    <option value="S" {{ request('jenis_kendaraan') == 'S' ? 'selected' : '' }}>S</option>
                    <option value="M" {{ request('jenis_kendaraan') == 'M' ? 'selected' : '' }}>M</option>
                    <option value="L" {{ request('jenis_kendaraan') == 'L' ? 'selected' : '' }}>L</option>
                    <option value="XL" {{ request('jenis_kendaraan') == 'XL' ? 'selected' : '' }}>XL</option>
                    <option value="Sport & Luxury" {{ request('jenis_kendaraan') == 'Sport & Luxury' ? 'selected' : '' }}>Sport & Luxury</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" 
                        class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                        :class="{ 'opacity-50 cursor-not-allowed': loading }"
                        :disabled="loading">
                    <span x-show="!loading">Filter Data</span>
                    <span x-show="loading" class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading...
                    </span>
                </button>
            </div>
        </form>
    </div>

    <!-- Recent Data Table -->
    <div class="bg-white shadow-lg rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Data HPP Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kendaraan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">HPP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Margin Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Margin Non-Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentData ?? [] as $data)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($created_at ?? now())->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $data->layanan_hpp ?? '-' }}</div>
                            <div class="text-sm text-gray-500">{{ $data->kategori_pendapatan ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $data->jenis_kendaraan ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($data->hpp ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if(($data->margin_member ?? 0) > 50) bg-green-100 text-green-800 
                                @elseif(($data->margin_member ?? 0) > 30) bg-yellow-100 text-yellow-800 
                                @else bg-red-100 text-red-800 @endif">
                                {{ number_format($data->margin_member ?? 0, 1) }}%
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if(($data->margin_non_member ?? 0) > 50) bg-green-100 text-green-800 
                                @elseif(($data->margin_non_member ?? 0) > 30) bg-yellow-100 text-yellow-800 
                                @else bg-red-100 text-red-800 @endif">
                                {{ number_format($data->margin_non_member ?? 0, 1) }}%
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Detail</a>
                            <a href="#" class="text-red-600 hover:text-red-900">Hapus</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-lg font-medium">Belum ada data HPP</p>
                            <p class="text-sm">Mulai dengan <a href="{{ route('hpp.index') }}" class="text-indigo-600 hover:text-indigo-900">menghitung HPP pertama</a></p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($recentData) && $recentData->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $recentData->links() }}
        </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('hpp.index') }}" class="group relative block p-6 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg text-white hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold">Hitung HPP Baru</h3>
                    <p class="text-blue-100">Tambah perhitungan HPP</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.components') }}" class="group relative block p-6 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg text-white hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold">Kelola Master Data</h3>
                    <p class="text-purple-100">Admin panel</p>
                </div>
            </div>
        </a>

        <div class="group relative block p-6 bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg text-white hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer" onclick="exportToExcel()">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold">Export Data</h3>
                    <p class="text-green-100">Download ke Excel</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Chart configurations
const chartConfig = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
        }
    }
};

// HPP vs Margin Chart
const hppMarginCtx = document.getElementById('hppMarginChart').getContext('2d');
new Chart(hppMarginCtx, {
    type: 'scatter',
    data: {
        datasets: [{
            label: 'Member',
            data: JSON.parse('@json($chartData['hppMargin']['member'] ?? [])'),
            backgroundColor: 'rgba(59, 130, 246, 0.6)',
            borderColor: 'rgba(59, 130, 246, 1)',
        }, {
            label: 'Non-Member',
            data: @json($chartData['hppMargin']['nonMember'] ?? []),
            backgroundColor: 'rgba(239, 68, 68, 0.6)',
            borderColor: 'rgba(239, 68, 68, 1)',
        }]
    },
    options: {
        ...chartConfig,
        scales: {
            x: {
                type: 'linear',
                position: 'bottom',
                title: {
                    display: true,
                    text: 'HPP (Rp)'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Margin (%)'
                }
            }
        }
    }
});

// Vehicle Type Distribution Chart
const vehicleTypeCtx = document.getElementById('vehicleTypeChart').getContext('2d');
new Chart(vehicleTypeCtx, {
    type: 'doughnut',
    data: {
        labels: @json(array_keys($chartData['vehicleDistribution'] ?? [])),
        datasets: [{
            data: @json(array_values($chartData['vehicleDistribution'] ?? [])),
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(239, 68, 68, 0.8)',
                'rgba(139, 92, 246, 0.8)'
            ],
            borderColor: [
                'rgba(59, 130, 246, 1)',
                'rgba(16, 185, 129, 1)',
                'rgba(245, 158, 11, 1)',
                'rgba(239, 68, 68, 1)',
                'rgba(139, 92, 246, 1)'
            ],
            borderWidth: 2
        }]
    },
    options: chartConfig
});

// Export function
function exportToExcel() {
    window.location.href = '{{ route("dashboard.export") }}';
}
</script>
@endpush
@endsection