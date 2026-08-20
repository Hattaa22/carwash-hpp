{{-- resources/views/hpp/result.blade.php --}}
@extends('layouts.app')

@section('title', 'Hasil Perhitungan HPP')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header Section --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Hasil Perhitungan HPP</h1>
                    <p class="text-gray-600">Detail analisis harga pokok produksi layanan car wash</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('hpp.form') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Hitung HPP Baru
                    </a>
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        </svg>
                        Dashboard
                    </a>
                </div>
            </div>
        </div>

        {{-- Alert Success --}}
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Left Column - Service Info --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Service Overview Card --}}
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                        <h2 class="text-xl font-semibold text-white">{{ $result->title ?? 'Detail Layanan' }}</h2>
                        <p class="text-blue-100 text-sm mt-1">{{ $result->timestamp ?? now()->format('d M Y H:i') }}</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                                    <div>
                                        <p class="text-sm text-gray-500">Sumber Pendapatan</p>
                                        <p class="font-semibold text-gray-900">{{ $result->sumber_pendapatan ?? 'Car Wash' }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                    <div>
                                        <p class="text-sm text-gray-500">Kategori Pendapatan</p>
                                        <p class="font-semibold text-gray-900">{{ $result->kategori_pendapatan ?? 'Premium' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                                    <div>
                                        <p class="text-sm text-gray-500">Jenis Kendaraan</p>
                                        <p class="font-semibold text-gray-900">{{ $result->jenis_kendaraan ?? 'M' }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-orange-500 rounded-full mr-3"></div>
                                    <div>
                                        <p class="text-sm text-gray-500">Layanan HPP</p>
                                        <p class="font-semibold text-gray-900">{{ $result->layanan_hpp ?? 'Touchless' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Calculation Details Card --}}
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Detail Perhitungan</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-blue-600 text-sm font-medium">Proporsi (ml)</p>
                                        <p class="text-2xl font-bold text-blue-800">{{ number_format($result->proporsi_ml ?? 0) }}</p>
                                    </div>
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2V7a2 2 0 012-2h2a2 2 0 002 2v2a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 00-2 2h-2a2 2 0 00-2 2v6a2 2 0 01-2 2H9z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-blue-600 text-xs mt-2">Decimal: {{ number_format($result->proporsi_decimal ?? 0, 3) }}</p>
                            </div>
                            
                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-green-600 text-sm font-medium">Pemakaian</p>
                                        <p class="text-2xl font-bold text-green-800">{{ number_format($result->pemakaian ?? 0, 2) }}</p>
                                    </div>
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-purple-50 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-purple-600 text-sm font-medium">Harga per ML</p>
                                        <p class="text-2xl font-bold text-purple-800">{{ number_format($result->harga_per_ml ?? 0) }}</p>
                                    </div>
                                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Margin Analysis Card --}}
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Analisis Margin</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Member --}}
                            <div class="border border-green-200 bg-green-50 rounded-lg p-4">
                                <div class="text-center mb-4">
                                    <h4 class="text-lg font-semibold text-green-800">Member</h4>
                                </div>
                                
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-green-700">Margin:</span>
                                        <span class="font-bold text-green-800">Rp {{ number_format($result->margin_member ?? 0) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-green-700">Persentase HPP:</span>
                                        <span class="font-bold text-green-800">{{ $result->persen_hpp_member ?? '0%' }}</span>
                                    </div>
                                </div>
                                
                                {{-- Progress Bar --}}
                                <div class="mt-4">
                                    <div class="bg-green-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ str_replace('%', '', $result->persen_hpp_member ?? '0%') }}%"></div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Non-Member --}}
                            <div class="border border-blue-200 bg-blue-50 rounded-lg p-4">
                                <div class="text-center mb-4">
                                    <h4 class="text-lg font-semibold text-blue-800">Non-Member</h4>
                                </div>
                                
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-blue-700">Margin:</span>
                                        <span class="font-bold text-blue-800">Rp {{ number_format($result->margin_non_member ?? 0) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-blue-700">Persentase HPP:</span>
                                        <span class="font-bold text-blue-800">{{ $result->persen_hpp_non_member ?? '0%' }}</span>
                                    </div>
                                </div>
                                
                                {{-- Progress Bar --}}
                                <div class="mt-4">
                                    <div class="bg-blue-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ str_replace('%', '', $result->persen_hpp_non_member ?? '0%') }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - HPP Summary & Actions --}}
            <div class="space-y-6">
                
                {{-- HPP Summary Card --}}
                <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-xl shadow-lg text-white overflow-hidden">
                    <div class="p-6">
                        <div class="text-center">
                            <h3 class="text-lg font-semibold mb-2">Total HPP</h3>
                            <div class="text-4xl font-bold mb-2">
                                Rp {{ number_format($result->hpp ?? 0) }}
                            </div>
                            <p class="text-orange-100 text-sm">Harga Pokok Produksi</p>
                        </div>
                        
                        <div class="mt-6 pt-6 border-t border-orange-300">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm">Perhitungan Selesai</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions Card --}}
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <button onclick="window.print()" 
                                class="w-full flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print Hasil
                        </button>
                        
                        <button onclick="exportToExcel()" 
                                class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export Excel
                        </button>
                        
                        <a href="{{ route('hpp.form') }}" 
                           class="w-full flex items-center justify-center px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Hitung Ulang
                        </a>
                    </div>
                </div>

                {{-- Info Card --}}
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200 p-6">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Informasi</h4>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Hasil perhitungan HPP telah disimpan ke database. 
                            Anda dapat melihat riwayat semua perhitungan di dashboard.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Print Styles --}}
<style>
@media print {
    body { -webkit-print-color-adjust: exact; }
    .no-print { display: none !important; }
}
</style>

{{-- JavaScript for Export --}}
<script>
function exportToExcel() {
    // Implementasi export ke Excel
    window.location.href = '{{ route("hpp.export", ["id" => $result->id ?? 1]) }}';
}

// Animation on load
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.bg-white, .bg-gradient-to-br');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endsection