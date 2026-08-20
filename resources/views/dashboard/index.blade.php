@extends('layouts.app')

@section('title', 'Dashboard Analytics - Sistem HPP Car Wash')

@section('content')
<div class="space-y-4 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Dashboard Overview</h1>
            <p class="text-xs text-slate-500 mt-0.5">Analisis HPP, margin profit, dan performa layanan car wash & treatment</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="exportToExcel()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs rounded-lg shadow-xs transition">
                <i data-lucide="download" class="w-3.5 h-3.5"></i>
                Export Data
            </button>
            <a href="{{ route('hpp.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-xs rounded-lg shadow-xs transition">
                <i data-lucide="calculator" class="w-3.5 h-3.5"></i>
                Hitung HPP Baru
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="p-2.5 bg-slate-100 text-slate-700 rounded-lg">
                <i data-lucide="file-text" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Total HPP Entries</p>
                <h3 class="text-lg font-bold text-slate-900 mt-0.5">{{ $statistics['total_results'] ?? $results->total() }}</h3>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="p-2.5 bg-blue-50 text-blue-600 rounded-lg">
                <i data-lucide="badge-dollar-sign" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Rata-rata HPP</p>
                <h3 class="text-lg font-bold text-slate-900 mt-0.5">Rp {{ number_format($statistics['avg_hpp'] ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="p-2.5 bg-slate-100 text-slate-700 rounded-lg">
                <i data-lucide="trending-up" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Margin Avg Member</p>
                <h3 class="text-lg font-bold text-slate-900 mt-0.5">{{ number_format($statistics['avg_margin_member'] ?? 0, 1) }}%</h3>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="p-2.5 bg-blue-50 text-blue-600 rounded-lg">
                <i data-lucide="sparkles" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">HPP Maksimal</p>
                <h3 class="text-lg font-bold text-slate-900 mt-0.5">Rp {{ number_format($statistics['max_hpp'] ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                <i data-lucide="bar-chart-2" class="w-4 h-4 text-blue-600"></i> Analisis HPP vs Margin
            </h3>
            <div class="h-52">
                <canvas id="hppMarginChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                <i data-lucide="pie-chart" class="w-4 h-4 text-blue-600"></i> Distribusi Jenis Kendaraan
            </h3>
            <div class="h-52">
                <canvas id="vehicleTypeChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white border border-slate-200/80 rounded-xl shadow-xs overflow-hidden">
        <div class="p-3.5 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-bold text-slate-900 text-xs flex items-center gap-2">
                <i data-lucide="list" class="w-3.5 h-3.5 text-blue-600"></i> Histori Kalkulasi HPP Terbaru
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Layanan</th>
                        <th class="px-4 py-3">Kendaraan</th>
                        <th class="px-4 py-3">Nilai HPP</th>
                        <th class="px-4 py-3">Margin Member</th>
                        <th class="px-4 py-3">Margin Non-Member</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($results ?? [] as $data)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-4 py-3 font-medium text-slate-900">
                            {{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-semibold text-slate-900 text-xs">{{ $data->layanan_hpp }}</div>
                            <div class="text-[10px] text-slate-400">{{ $data->sumber_pendapatan }} - {{ $data->kategori_pendapatan }}</div>
                        </td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $data->jenis_kendaraan }}</td>
                        <td class="px-4 py-3 font-semibold text-blue-600">Rp {{ number_format($data->hpp, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Rp {{ number_format($data->margin_member, 0, ',', '.') }} ({{ number_format($data->persen_hpp_member, 1) }}%)
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                Rp {{ number_format($data->margin_non_member, 0, ',', '.') }} ({{ number_format($data->persen_hpp_non_member, 1) }}%)
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-400">
                            <i data-lucide="calculator" class="w-7 h-7 mx-auto mb-2 text-slate-300"></i>
                            <p class="text-xs font-medium">Belum ada data kalkulasi HPP.</p>
                            <a href="{{ route('hpp.index') }}" class="text-[11px] text-blue-600 hover:underline mt-1 inline-block">Mulai hitung HPP pertama</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($results) && $results->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">
            {{ $results->links() }}
        </div>
        @endif
    </div>
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
            data: @json($chartData['hppMargin']['member'] ?? []),
            backgroundColor: 'rgba(37, 99, 235, 0.7)',
            borderColor: 'rgba(37, 99, 235, 1)',
        }, {
            label: 'Non-Member',
            data: @json($chartData['hppMargin']['nonMember'] ?? []),
            backgroundColor: 'rgba(15, 23, 42, 0.7)',
            borderColor: 'rgba(15, 23, 42, 1)',
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
                'rgba(37, 99, 235, 0.85)',
                'rgba(15, 23, 42, 0.85)',
                'rgba(59, 130, 246, 0.65)',
                'rgba(51, 65, 85, 0.65)',
                'rgba(148, 163, 184, 0.65)'
            ],
            borderColor: [
                '#2563eb',
                '#0f172a',
                '#3b82f6',
                '#334155',
                '#94a3b8'
            ],
            borderWidth: 1.5
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