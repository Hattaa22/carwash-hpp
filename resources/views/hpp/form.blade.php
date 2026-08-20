@extends('layouts.app')

@section('title', 'Input HPP - Car Wash System')

@section('content')
<div class="bg-white rounded-lg shadow-lg p-6">

        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Perhitungan HPP Layanan</h2>
            <p class="text-gray-600">Input data untuk menghitung Harga Pokok Produksi layanan car wash dan treatment</p>
        </div>

        <form @submit.prevent="submitForm" class="space-y-6">
            @csrf
            
            <!-- Grid Layout untuk form -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    
                    <!-- STEP 3: Sumber Pendapatan dengan Multiple Fallbacks -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sumber Pendapatan</label>
                        
                        <!-- Method 1: Alpine Template (Primary) -->
                        <select x-model="form.sumber_pendapatan" @change="onSumberChange" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Sumber Pendapatan</option>
                            
                            <!-- Alpine Template Options -->
                            <template x-for="(sumber, index) in availableSumberPendapatan" :key="'sumber-' + index">
                                <option :value="sumber" x-text="sumber"></option>
                            </template>
                            
                            <!-- FALLBACK: Blade Static Options (jika Alpine gagal) -->
                            @if(!empty($sumberPendapatanList))
                                @foreach($sumberPendapatanList as $sumber)
                                    <option value="{{ $sumber }}">{{ $sumber }}</option>
                                @endforeach
                            @endif
                        </select>
                        
                    </div>

                    <!-- STEP 4: Rest of the form dengan error handling -->
                    <!-- Kategori Pendapatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Pendapatan</label>
                        <select x-model="form.kategori_pendapatan" @change="onKategoriChange" 
                                :disabled="!form.sumber_pendapatan || loading.kategori"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100">
                            <option value="">Pilih Kategori Pendapatan</option>
                            <template x-for="(kategori, index) in availableKategori" :key="'kategori-' + index">
                                <option :value="kategori" x-text="kategori"></option>
                            </template>
                            @foreach($service_categories as $kategori)
                                            <option value="{{ $kategori->kategori_pendapatan }}">
                                                {{ $kategori->kategori_pendapatan }} 
                                            </option>
                            @endforeach
                        <div x-show="loading.kategori" class="text-sm text-blue-500 mt-1">Memuat kategori...</div>
                        <div x-show="!loading.kategori && availableKategori.length > 0" class="text-xs text-gray-400 mt-1" 
                             x-text="'Tersedia: ' + availableKategori.length + ' kategori'"></div>
                        </select>
                    </div>

                    <!-- Layanan HPP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Layanan HPP</label>
                        <select x-model="form.layanan_hpp" @change="onLayananChange" 
                                :disabled="!form.kategori_pendapatan || loading.layanan"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100">
                            <option value="">Pilih Layanan HPP</option>
                            <template x-for="layanan in availableLayanan" :key="'layanan-' + layanan.id">
                                <option :value="layanan.layanan_hpp" x-text="layanan.layanan_hpp"></option>
                            </template>
                            @foreach($service_categories as $layanan)
                                            <option value="{{ $layanan->layanan_hpp }}">
                                                {{ $layanan->layanan_hpp }} 
                                            </option>
                            @endforeach
                        </select>
                        <div x-show="loading.layanan" class="text-sm text-blue-500 mt-1">Memuat layanan...</div>
                        <div x-show="!loading.layanan && availableLayanan.length > 0" class="text-xs text-gray-400 mt-1" 
                             x-text="'Tersedia: ' + availableLayanan.length + ' layanan'"></div>
                    </div>

                    <!-- Jenis Kendaraan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kendaraan</label>
                        <select x-model="form.jenis_kendaraan" @change="onJenisKendaraanChange" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Jenis Kendaraan</option>
                            <template x-for="vehicle in vehicles" :key="'vehicle-' + vehicle.id">
                                <option :value="vehicle.jenis_kendaraan" x-text="vehicle.jenis_kendaraan"></option>
                            </template>
                            @foreach($vehicles as $vehicle)
                                            <option value="{{ $vehicle->jenis_kendaraan }}">
                                                {{ $vehicle->jenis_kendaraan }} 
                                            </option>
                            @endforeach
                        </select>
                        <div x-show="!loading.vehicles && vehicles.length > 0" class="text-xs text-gray-400 mt-1" 
                             x-text="'Tersedia: ' + vehicles.length + ' jenis kendaraan'"></div>
                    </div>

                    <!-- Proporsi ML -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Proporsi (ml)
                            <span class="text-sm text-green-600" x-show="form.proporsi_ml > 0 && selectedLayanan">(Auto-filled)</span>
                        </label>
                        <input type="number" x-model="form.proporsi_ml" @input="calculateIfReady" step="0.01" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0.00">
                    </div>

                    <!-- Additional Input Fields for Calculation -->
                    <div class="border-t pt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Data Tambahan untuk Perhitungan</h4>
                        
                        <!-- Harga Beli per Liter -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga Beli per Liter (Rp)</label>
                            <input type="number" x-model="form.harga_beli_per_liter" @input="calculateIfReady" step="0.01" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="0.00">
                        </div>

                        <!-- Harga Jual Member -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga Jual Member (Rp)</label>
                            <input type="number" x-model="form.harga_jual_member" @input="calculateIfReady" step="0.01" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="0.00">
                        </div>

                        <!-- Harga Jual Non-Member -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga Jual Non-Member (Rp)</label>
                            <input type="number" x-model="form.harga_jual_non_member" @input="calculateIfReady" step="0.01" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="0.00">
                        </div>
                    </div>
                </div>

                <!-- Right Column - Results -->
                <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Hasil Perhitungan HPP</h3>
                    
                    <!-- Results akan muncul disini setelah calculation -->
                    <div x-show="!loading.calculation && calculated.hpp === 0" class="text-center py-8 text-gray-500">
                        <p>Lengkapi semua field untuk melihat hasil perhitungan</p>
                    </div>
                    
                    <!-- HPP Results -->
                    <div x-show="!loading.calculation && calculated.hpp > 0" class="space-y-4">
                        <!-- HPP Utama -->
                        <div class="bg-white rounded-lg p-4 border">
                            <label class="block text-sm font-medium text-gray-700 mb-1">HPP (Harga Pokok Produksi)</label>
                            <div class="text-2xl font-bold text-green-600" x-text="formatCurrency(calculated.hpp || 0)"></div>
                        </div>

                        <!-- Detail Perhitungan -->
                        <div class="bg-white rounded-lg p-4 border">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Detail Perhitungan</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Proporsi (Desimal):</span>
                                    <span x-text="calculated.proporsi_decimal.toFixed(4)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Pemakaian (ml):</span>
                                    <span x-text="calculated.pemakaian.toFixed(2) + ' ml'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Harga per ml:</span>
                                    <span x-text="formatCurrency(calculated.harga_per_ml)"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Margin Analysis -->
                        <div class="bg-white rounded-lg p-4 border">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Analisis Margin</h4>
                            <div class="space-y-3">
                                <!-- Member -->
                                <div class="bg-blue-50 p-3 rounded">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-medium text-blue-800">Member</span>
                                        <span class="text-blue-600" x-text="calculated.persen_hpp_member.toFixed(1) + '%'"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-blue-600">Margin:</span>
                                        <span class="font-medium text-blue-800" x-text="formatCurrency(calculated.margin_member)"></span>
                                    </div>
                                </div>

                                <!-- Non-Member -->
                                <div class="bg-green-50 p-3 rounded">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-medium text-green-800">Non-Member</span>
                                        <span class="text-green-600" x-text="calculated.persen_hpp_non_member.toFixed(1) + '%'"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-green-600">Margin:</span>
                                        <span class="font-medium text-green-800" x-text="formatCurrency(calculated.margin_non_member)"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loading State -->
                    <div x-show="loading.calculation" class="text-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="text-blue-600 mt-2">Menghitung HPP...</p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-6 border-t">
                <button type="submit" :disabled="!isFormValid() || loading.submit" 
                        class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white px-8 py-3 rounded-lg font-medium">
                    <span x-show="!loading.submit">Simpan Data HPP</span>
                    <span x-show="loading.submit">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- STEP 5: Improved Data Passing -->
<script>
    // CSRF Token Setup
    window.csrfToken = '{{ csrf_token() }}';
    
    // Pass data with validation
    window.hppData = {
        vehicles: @json($vehicles ?? []),
        service_categories: @json($service_categories ?? []),
        sumberPendapatanList: @json($sumberPendapatanList ?? []),
        components: @json($components ?? [])
    };
    
    // Debug logging
    console.log('🔧 DEBUGGING HPP DATA INTEGRATION');
    console.log('📊 Window hppData:', window.hppData);
    console.log('🎯 Sumber Pendapatan Count:', window.hppData.sumberPendapatanList.length);
    console.log('📋 Sumber Pendapatan List:', window.hppData.sumberPendapatanList);
    
    // Check for common issues
    if (!window.hppData.sumberPendapatanList || window.hppData.sumberPendapatanList.length === 0) {
        console.error('❌ CRITICAL: No sumberPendapatanList data from controller!');
        console.log('🔍 Check HppController@index method');
        console.log('🔍 Check service_categories table data');
    } else {
        console.log('✅ SUCCESS: Sumber pendapatan data loaded from controller');
    }
</script>

@endsection

@section('scripts')
<script>
function hppCalculator() {
    return {
        // Form data
        form: {
            sumber_pendapatan: '',
            kategori_pendapatan: '',
            layanan_hpp: '',
            jenis_kendaraan: '',
            proporsi_ml: 0,
            harga_beli_per_liter: 0,
            harga_jual_member: 0,
            harga_jual_non_member: 0
        },
        
        // Calculated results
        calculated: {
            proporsi_decimal: 0,
            pemakaian: 0,
            harga_per_ml: 0,
            hpp: 0,
            margin_member: 0,
            margin_non_member: 0,
            persen_hpp_member: 0,
            persen_hpp_non_member: 0
        },
        
        // Data arrays
        availableSumberPendapatan: [],
        availableKategori: [],
        availableLayanan: [],
        vehicles: [],
        service_categories: [],
        components: [],
        
        // Selected objects
        selectedLayanan: null,
        selectedVehicle: null,
        
        // Loading states
        loading: {
            sumber: false,
            kategori: false,
            layanan: false,
            vehicles: false,
            calculation: false,
            submit: false
        },
        
        // Debug flag
        alpineReady: false,
        
        // STEP 6: Enhanced initialization dengan comprehensive error checking
        init() {
            console.log('🚀 INITIALIZING HPP CALCULATOR');
            console.log('🔧 Alpine.js component starting...');
            
            try {
                this.loadInitialData();
                this.alpineReady = true;
                console.log('✅ Alpine component initialized successfully');
            } catch (error) {
                console.error('❌ Error initializing Alpine component:', error);
                this.alpineReady = false;
            }
        },
        
        // STEP 7: Improved data loading dengan multiple fallbacks
        loadInitialData() {
            console.log('📊 Loading data from window.hppData...');
            
            // Method 1: Load dari window.hppData (primary)
            if (window.hppData) {
                this.vehicles = Array.isArray(window.hppData.vehicles) ? window.hppData.vehicles : [];
                this.service_categories = Array.isArray(window.hppData.service_categories) ? window.hppData.service_categories : [];
                this.availableSumberPendapatan = Array.isArray(window.hppData.sumberPendapatanList) ? window.hppData.sumberPendapatanList : [];
                this.components = Array.isArray(window.hppData.components) ? window.hppData.components : [];
                
                console.log('✅ Data loaded from window.hppData');
            } else {
                console.warn('⚠️ window.hppData not found, trying direct variables...');
                
                // Method 2: Fallback ke global variables
                if (typeof vehicles !== 'undefined') this.vehicles = Array.isArray(vehicles) ? vehicles : [];
                if (typeof service_categories !== 'undefined') this.service_categories = Array.isArray(service_categories) ? service_categories : [];
                if (typeof sumberPendapatanList !== 'undefined') this.availableSumberPendapatan = Array.isArray(sumberPendapatanList) ? sumberPendapatanList : [];
                if (typeof components !== 'undefined') this.components = Array.isArray(components) ? components : [];
            }
            
            // Debug loaded data
            console.log('📋 Final loaded data:');
            console.log('  - Vehicles:', this.vehicles.length, 'records');
            console.log('  - Service Categories:', this.service_categories.length, 'records');
            console.log('  - Sumber Pendapatan:', this.availableSumberPendapatan.length, 'items');
            console.log('  - Components:', this.components.length, 'items');
            
            // Validation checks
            if (this.availableSumberPendapatan.length === 0) {
                console.error('❌ CRITICAL: availableSumberPendapatan is empty!');
                this.showToast('Data sumber pendapatan tidak tersedia. Periksa database.', 'error');
            } else {
                console.log('✅ Sumber pendapatan data loaded successfully');
            }
        },
        
        // STEP 8: Enhanced event handlers dengan better error handling
        async onSumberChange() {
            console.log('🔄 Sumber pendapatan changed to:', this.form.sumber_pendapatan);
            
            // Reset dependent fields
            this.form.kategori_pendapatan = "";
            this.form.layanan_hpp = "";
            this.form.proporsi_ml = 0;
            this.selectedLayanan = null;
            this.availableKategori = [];
            this.availableLayanan = [];
            this.resetCalculation();
            
            if (this.form.sumber_pendapatan) {
                await this.loadKategoriBySource();
            }
        },
        
        async loadKategoriBySource() {
            this.loading.kategori = true;
            console.log('📡 Loading kategori for:', this.form.sumber_pendapatan);
            
            try {
                const response = await fetch('{{ route("hpp.kategori-by-source") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({
                        sumber_pendapatan: this.form.sumber_pendapatan
                    })
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.availableKategori = Array.isArray(data) ? data : [];
                    console.log('✅ Kategori loaded:', this.availableKategori);
                } else {
                    const errorText = await response.text();
                    console.error('❌ HTTP Error:', response.status, errorText);
                    throw new Error(`Server error: ${response.status}`);
                }
            } catch (error) {
                console.error('❌ Error loading kategori:', error);
                this.showToast('Gagal memuat kategori: ' + error.message, 'error');
                this.availableKategori = [];
            } finally {
                this.loading.kategori = false;
            }
        },
        
        // Kategori change handler
        async onKategoriChange() {
            console.log('🔄 Kategori pendapatan changed to:', this.form.kategori_pendapatan);
            
            // Reset dependent fields
            this.form.layanan_hpp = "";
            this.form.proporsi_ml = 0;
            this.selectedLayanan = null;
            this.availableLayanan = [];
            this.resetCalculation();
            
            if (this.form.kategori_pendapatan) {
                await this.loadLayananByKategori();
            }
        },
        
        async loadLayananByKategori() {
            this.loading.layanan = true;
            console.log('📡 Loading layanan for kategori:', this.form.kategori_pendapatan);
            
            try {
                const response = await fetch('{{ route("hpp.layanan-by-kategori") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({
                        sumber_pendapatan: this.form.sumber_pendapatan,
                        kategori_pendapatan: this.form.kategori_pendapatan
                    })
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.availableLayanan = Array.isArray(data) ? data : [];
                    console.log('✅ Layanan loaded:', this.availableLayanan);
                } else {
                    console.error('❌ HTTP Error:', response.status);
                    throw new Error(`Server error: ${response.status}`);
                }
            } catch (error) {
                console.error('❌ Error loading layanan:', error);
                this.showToast('Gagal memuat layanan: ' + error.message, 'error');
                this.availableLayanan = [];
            } finally {
                this.loading.layanan = false;
            }
        },
        
        // Layanan change handler
        async onLayananChange() {
            console.log('🔄 Layanan HPP changed to:', this.form.layanan_hpp);
            
            this.resetCalculation();
            
            if (this.form.layanan_hpp) {
                // Find selected layanan object
                this.selectedLayanan = this.availableLayanan.find(layanan => 
                    layanan.layanan_hpp === this.form.layanan_hpp
                );
                
                if (this.selectedLayanan) {
                    console.log('✅ Selected layanan:', this.selectedLayanan);
                    
                    // Auto-fill proporsi if available
                    if (this.selectedLayanan.proporsi_ml) {
                        this.form.proporsi_ml = parseFloat(this.selectedLayanan.proporsi_ml);
                        console.log('🔄 Auto-filled proporsi:', this.form.proporsi_ml);
                    }
                    
                    // Auto-fill pricing if available
                    if (this.selectedLayanan.harga_beli_per_liter) {
                        this.form.harga_beli_per_liter = parseFloat(this.selectedLayanan.harga_beli_per_liter);
                    }
                    if (this.selectedLayanan.harga_jual_member) {
                        this.form.harga_jual_member = parseFloat(this.selectedLayanan.harga_jual_member);
                    }
                    if (this.selectedLayanan.harga_jual_non_member) {
                        this.form.harga_jual_non_member = parseFloat(this.selectedLayanan.harga_jual_non_member);
                    }
                    
                    // Trigger calculation
                    await this.calculateIfReady();
                }
            } else {
                this.selectedLayanan = null;
            }
        },
        
        // Jenis kendaraan change handler
        onJenisKendaraanChange() {
            console.log('🔄 Jenis kendaraan changed to:', this.form.jenis_kendaraan);
            
            if (this.form.jenis_kendaraan) {
                this.selectedVehicle = this.vehicles.find(vehicle => 
                    vehicle.jenis_kendaraan === this.form.jenis_kendaraan
                );
                
                if (this.selectedVehicle) {
                    console.log('✅ Selected vehicle:', this.selectedVehicle);
                }
            } else {
                this.selectedVehicle = null;
            }
            
            this.calculateIfReady();
        },
        
        // Main calculation method
        async calculateIfReady() {
            console.log('🧮 Checking if ready to calculate...');
            
            // Check if all required fields are filled
            if (!this.form.sumber_pendapatan || 
                !this.form.kategori_pendapatan || 
                !this.form.layanan_hpp || 
                !this.form.jenis_kendaraan || 
                !this.form.proporsi_ml ||
                !this.form.harga_beli_per_liter ||
                !this.form.harga_jual_member ||
                !this.form.harga_jual_non_member) {
                
                console.log('⏳ Not all fields filled, skipping calculation');
                this.resetCalculation();
                return;
            }
            
            this.loading.calculation = true;
            
            try {
                console.log('🧮 Starting HPP calculation...');
                
                // Step 1: Convert proporsi to decimal (proporsi_ml / 1000)
                this.calculated.proporsi_decimal = parseFloat(this.form.proporsi_ml) / 1000;
                
                // Step 2: Calculate pemakaian (for display purposes, same as proporsi_ml)
                this.calculated.pemakaian = parseFloat(this.form.proporsi_ml);
                
                // Step 3: Calculate harga per ml (harga_beli_per_liter / 1000)
                this.calculated.harga_per_ml = parseFloat(this.form.harga_beli_per_liter) / 1000;
                
                // Step 4: Calculate HPP (proporsi_decimal * harga_beli_per_liter)
                this.calculated.hpp = this.calculated.proporsi_decimal * parseFloat(this.form.harga_beli_per_liter);
                
                // Step 5: Calculate margins
                this.calculated.margin_member = parseFloat(this.form.harga_jual_member) - this.calculated.hpp;
                this.calculated.margin_non_member = parseFloat(this.form.harga_jual_non_member) - this.calculated.hpp;
                
                // Step 6: Calculate percentage of HPP against selling price
                this.calculated.persen_hpp_member = (this.calculated.hpp / parseFloat(this.form.harga_jual_member)) * 100;
                this.calculated.persen_hpp_non_member = (this.calculated.hpp / parseFloat(this.form.harga_jual_non_member)) * 100;
                
                console.log('✅ HPP Calculation completed:');
                console.log('📊 Results:', this.calculated);
                
                // Log calculation details
                console.log('🔢 Calculation Details:');
                console.log(`  Proporsi ML: ${this.form.proporsi_ml}`);
                console.log(`  Proporsi Decimal: ${this.calculated.proporsi_decimal}`);
                console.log(`  Harga Beli per Liter: ${this.form.harga_beli_per_liter}`);
                console.log(`  Harga per ML: ${this.calculated.harga_per_ml}`);
                console.log(`  HPP: ${this.calculated.hpp}`);
                console.log(`  Margin Member: ${this.calculated.margin_member}`);
                console.log(`  Margin Non-Member: ${this.calculated.margin_non_member}`);
                
            } catch (error) {
                console.error('❌ Error in calculation:', error);
                this.showToast('Error dalam perhitungan: ' + error.message, 'error');
                this.resetCalculation();
            } finally {
                this.loading.calculation = false;
            }
        },
        
        // Submit form method
        async submitForm() {
            if (!this.isFormValid()) {
                this.showToast('Harap lengkapi semua field dan pastikan perhitungan telah dilakukan', 'error');
                return;
            }
            
            this.loading.submit = true;
            
            try {
                console.log('📤 Submitting HPP data...');
                
                const submitData = {
                    ...this.form,
                    calculated: this.calculated,
                    selectedLayanan: this.selectedLayanan,
                    selectedVehicle: this.selectedVehicle
                };
                
                console.log('📋 Submit data:', submitData);
                
                const response = await fetch('{{ route("hpp.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify(submitData)
                });
                
                if (response.ok) {
                    const result = await response.json();
                    console.log('✅ HPP data saved successfully:', result);
                    this.showToast('Data HPP berhasil disimpan!', 'success');
                    
                    // Optional: Reset form after successful submission
                    if (confirm('Data berhasil disimpan. Apakah Anda ingin membuat perhitungan baru?')) {
                        this.resetForm();
                    }
                } else {
                    const errorText = await response.text();
                    console.error('❌ Submit error:', response.status, errorText);
                    throw new Error(`Server error: ${response.status}`);
                }
                
            } catch (error) {
                console.error('❌ Error submitting form:', error);
                this.showToast('Gagal menyimpan data: ' + error.message, 'error');
            } finally {
                this.loading.submit = false;
            }
        },
        
        // Utility methods
        formatCurrency(value) {
            if (!value || isNaN(value)) return 'Rp 0';
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(value));
        },
        
        formatNumber(value, decimals = 2) {
            if (!value || isNaN(value)) return '0';
            return parseFloat(value).toFixed(decimals);
        },
        
        isFormValid() {
            const formValid = this.form.sumber_pendapatan && 
                             this.form.kategori_pendapatan && 
                             this.form.layanan_hpp && 
                             this.form.jenis_kendaraan && 
                             this.form.proporsi_ml > 0 &&
                             this.form.harga_beli_per_liter > 0 &&
                             this.form.harga_jual_member > 0 &&
                             this.form.harga_jual_non_member > 0;
            
            const calculationValid = this.calculated.hpp > 0;
            
            return formValid && calculationValid;
        },
        
        resetCalculation() {
            this.calculated = {
                proporsi_decimal: 0, 
                pemakaian: 0, 
                harga_per_ml: 0, 
                hpp: 0,
                margin_member: 0, 
                margin_non_member: 0, 
                persen_hpp_member: 0, 
                persen_hpp_non_member: 0
            };
        },
        
        resetForm() {
            this.form = { 
                sumber_pendapatan: '', 
                kategori_pendapatan: '', 
                layanan_hpp: '', 
                jenis_kendaraan: '', 
                proporsi_ml: 0,
                harga_beli_per_liter: 0,
                harga_jual_member: 0,
                harga_jual_non_member: 0
            };
            this.resetCalculation();
            this.availableKategori = [];
            this.availableLayanan = [];
            this.selectedLayanan = null;
            this.selectedVehicle = null;
        },
        
        showToast(message, type = 'info') {
            console.log(`${type.toUpperCase()}: ${message}`);
            
            // Simple alert implementation (you can replace with toast library)
            if (type === 'success') {
                alert('✅ ' + message);
            } else if (type === 'error') {
                alert('❌ ' + message);
            } else {
                alert('ℹ️ ' + message);
            }
        },
        
        // Additional helper methods
        validateNumericInput(value) {
            const numValue = parseFloat(value);
            return !isNaN(numValue) && numValue >= 0;
        },
        
        // Method to recalculate when any input changes
        async handleInputChange(field, value) {
            console.log(`🔄 Input changed: ${field} = ${value}`);
            
            if (field.includes('harga') || field === 'proporsi_ml') {
                if (!this.validateNumericInput(value)) {
                    console.warn(`⚠️ Invalid numeric value for ${field}: ${value}`);
                    return;
                }
            }
            
            // Trigger recalculation for relevant fields
            if (['proporsi_ml', 'harga_beli_per_liter', 'harga_jual_member', 'harga_jual_non_member'].includes(field)) {
                await this.calculateIfReady();
            }
        },
        
        // Method to get calculation summary
        getCalculationSummary() {
            return {
                form_data: this.form,
                calculated_results: this.calculated,
                is_valid: this.isFormValid(),
                selected_layanan: this.selectedLayanan,
                selected_vehicle: this.selectedVehicle,
                timestamp: new Date().toISOString()
            };
        },
        
        // Method to export calculation results
        exportCalculation() {
            const summary = this.getCalculationSummary();
            const dataStr = JSON.stringify(summary, null, 2);
            const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
            
            const exportFileDefaultName = `hpp_calculation_${new Date().toISOString().split('T')[0]}.json`;
            
            const linkElement = document.createElement('a');
            linkElement.setAttribute('href', dataUri);
            linkElement.setAttribute('download', exportFileDefaultName);
            linkElement.click();
        },
        
        // Method to print calculation results
        printCalculation() {
            if (!this.isFormValid()) {
                this.showToast('Tidak ada data yang valid untuk dicetak', 'error');
                return;
            }
            
            const printContent = `
                <html>
                <head>
                    <title>Hasil Perhitungan HPP</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 30px; }
                        .section { margin-bottom: 20px; }
                        .row { display: flex; justify-content: space-between; padding: 5px 0; }
                        .highlight { background-color: #f0f9ff; padding: 10px; border-radius: 5px; }
                        .total { font-size: 18px; font-weight: bold; color: #059669; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>Hasil Perhitungan HPP</h1>
                        <p>Tanggal: ${new Date().toLocaleDateString('id-ID')}</p>
                    </div>
                    
                    <div class="section">
                        <h3>Data Input</h3>
                        <div class="row"><span>Sumber Pendapatan:</span><span>${this.form.sumber_pendapatan}</span></div>
                        <div class="row"><span>Kategori Pendapatan:</span><span>${this.form.kategori_pendapatan}</span></div>
                        <div class="row"><span>Layanan HPP:</span><span>${this.form.layanan_hpp}</span></div>
                        <div class="row"><span>Jenis Kendaraan:</span><span>${this.form.jenis_kendaraan}</span></div>
                        <div class="row"><span>Proporsi (ml):</span><span>${this.form.proporsi_ml}</span></div>
                        <div class="row"><span>Harga Beli per Liter:</span><span>${this.formatCurrency(this.form.harga_beli_per_liter)}</span></div>
                        <div class="row"><span>Harga Jual Member:</span><span>${this.formatCurrency(this.form.harga_jual_member)}</span></div>
                        <div class="row"><span>Harga Jual Non-Member:</span><span>${this.formatCurrency(this.form.harga_jual_non_member)}</span></div>
                    </div>
                    
                    <div class="section">
                        <h3>Hasil Perhitungan</h3>
                        <div class="highlight">
                            <div class="row total"><span>HPP (Harga Pokok Produksi):</span><span>${this.formatCurrency(this.calculated.hpp)}</span></div>
                        </div>
                        <div class="row"><span>Proporsi (Desimal):</span><span>${this.calculated.proporsi_decimal.toFixed(4)}</span></div>
                        <div class="row"><span>Harga per ml:</span><span>${this.formatCurrency(this.calculated.harga_per_ml)}</span></div>
                    </div>
                    
                    <div class="section">
                        <h3>Analisis Margin</h3>
                        <div class="row"><span>Margin Member:</span><span>${this.formatCurrency(this.calculated.margin_member)} (${this.calculated.persen_hpp_member.toFixed(1)}%)</span></div>
                        <div class="row"><span>Margin Non-Member:</span><span>${this.formatCurrency(this.calculated.margin_non_member)} (${this.calculated.persen_hpp_non_member.toFixed(1)}%)</span></div>
                    </div>
                </body>
                </html>
            `;
            
            const printWindow = window.open('', '_blank');
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.print();
        }
    }
}
</script>
@endsection