@extends('layouts.app')

@section('title', 'Kelola Jenis Kendaraan')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Kelola Jenis Kendaraan</h1>
                    <p class="text-gray-600">Manage data kendaraan, volume campuran, dan harga jual</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Kendaraan
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Kendaraan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ count($vehicles) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Rata-rata Volume</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format(collect($vehicles)->avg('volume_campuran'), 0) }}ml</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Harga Member Tertinggi</p>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format(collect($vehicles)->max('harga_member'), 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="bg-orange-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-8-2h.01M8 21l4-7 4 7M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Harga Non-Member Tertinggi</p>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format(collect($vehicles)->max('harga_non_member'), 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehicles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($vehicles as $vehicle)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                                {{ $vehicle['id'] }}
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $vehicle['jenis_kendaraan'] }}</h3>
                                <p class="text-sm text-gray-500">id: {{ $vehicle['id'] }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-1">
                            <button onclick="editVehicle('{{ $vehicle['id'] }}')" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button onclick="deleteVehicle('{{ $vehicle['id'] }}')" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Volume Campuran</span>
                            <span class="text-sm font-medium text-gray-900">{{ number_format($vehicle['volume_campuran'], 0) }} ml</span>
                        </div>
                        
                        <div class="border-t border-gray-100 pt-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600">Harga Member</span>
                                <span class="text-sm font-medium text-green-600">Rp {{ number_format($vehicle['harga_member'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Harga Non-Member</span>
                                <span class="text-sm font-medium text-blue-600">Rp {{ number_format($vehicle['harga_non_member'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-100 pt-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Selisih Harga</span>
                                <span class="text-sm font-medium text-orange-600">Rp {{ number_format($vehicle['harga_non_member'] - $vehicle['harga_member'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Price Comparison Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Perbandingan Harga</h3>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600">Member</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600">Non-Member</span>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <div class="flex space-x-4 min-w-full">
                    @foreach($vehicles as $vehicle)
                    <div class="flex-shrink-0 w-32 text-center">
                        <div class="mb-2">
                            <div class="text-xs font-medium text-gray-600 mb-1">{{ $vehicle['id'] }}</div>
                            <div class="relative">
                                <div class="bg-gray-200 h-32 rounded-lg overflow-hidden">
                                    <div class="bg-green-500 w-full rounded-lg transition-all duration-1000 ease-out" style="height: {{ ($vehicle['harga_member'] / max(collect($vehicles)->max('harga_member'), collect($vehicles)->max('harga_non_member'))) * 100 }}%"></div>
                                </div>
                                <div class="bg-gray-200 h-32 rounded-lg overflow-hidden mt-2">
                                    <div class="bg-blue-500 w-full rounded-lg transition-all duration-1000 ease-out" style="height: {{ ($vehicle['harga_non_member'] / max(collect($vehicles)->max('harga_member'), collect($vehicles)->max('harga_non_member'))) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="text-xs text-gray-500">
                            <div>{{ number_format($vehicle['harga_member'] / 1000) }}k</div>
                            <div>{{ number_format($vehicle['harga_non_member'] / 1000) }}k</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Vehicle Modal -->
<div id="vehicleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Tambah Kendaraan</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="vehicleForm" class="space-y-4">
                <input type="hidden" id="originalId" name="original_id">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="id" class="block text-sm font-medium text-gray-700 mb-1">ID</label>
                        <input type="text" id="id" name="id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="S, M, L, XL">
                    </div>
                    <div>
                        <label for="jenis_kendaraan" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kendaraan</label>
                        <input type="text" id="jenis_kendaraan" name="jenis_kendaraan" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Small, Medium, Large">
                    </div>
                </div>
                
                <div>
                    <label for="volume_campuran" class="block text-sm font-medium text-gray-700 mb-1">Volume Campuran (ml)</label>
                    <input type="number" id="volume_campuran" name="volume_campuran" required min="0" step="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="750">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="harga_member" class="block text-sm font-medium text-gray-700 mb-1">Harga Member</label>
                        <input type="number" id="harga_member" name="harga_member" required min="0" step="1000" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="55000">
                    </div>
                    <div>
                        <label for="harga_non_member" class="block text-sm font-medium text-gray-700 mb-1">Harga Non-Member</label>
                        <input type="number" id="harga_non_member" name="harga_non_member" required min="0" step="1000" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="75000">
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Selisih Harga:</span>
                        <span id="selisihHarga" class="font-medium text-orange-600">Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between text-sm mt-1">
                        <span class="text-gray-600">Margin (%):</span>
                        <span id="marginPersen" class="font-medium text-blue-600">0%</span>
                    </div>
                </div>
                
                <div class="flex space-x-3 pt-4">
                    <button type="submit" id="submitBtn" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition-colors duration-200">
                        Simpan
                    </button>
                    <button type="button" onclick="closeModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded-lg font-medium transition-colors duration-200">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-60">
    <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
        <span class="text-gray-700">Menyimpan data...</span>
    </div>
</div>

<script>
// Auto calculate selisih and margin
document.getElementById('harga_member').addEventListener('input', calculateDifference);
document.getElementById('harga_non_member').addEventListener('input', calculateDifference);

function calculateDifference() {
    const hargaMember = parseFloat(document.getElementById('harga_member').value) || 0;
    const hargaNonMember = parseFloat(document.getElementById('harga_non_member').value) || 0;
    const selisih = hargaNonMember - hargaMember;
    const margin = hargaMember > 0 ? ((selisih / hargaMember) * 100) : 0;
    
    document.getElementById('selisihHarga').textContent = `Rp ${selisih.toLocaleString('id-ID')}`;
    document.getElementById('marginPersen').textContent = `${margin.toFixed(1)}%`;
}

// Modal functions
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Kendaraan';
    document.getElementById('vehicleForm').reset();
    document.getElementById('originalId').value = '';
    document.getElementById('selisihHarga').textContent = 'Rp 0';
    document.getElementById('marginPersen').textContent = '0%';
    document.getElementById('vehicleModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('vehicleModal').classList.add('hidden');
}

function showLoading() {
    document.getElementById('loadingOverlay').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loadingOverlay').classList.add('hidden');
}

function editVehicle(id) {
    showLoading();
    
    // Fetch vehicle data and populate form
    fetch(`/api/vehicles/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('modalTitle').textContent = 'Edit Kendaraan';
            document.getElementById('originalId').value = data.id;
            document.getElementById('id').value = data.id;
            document.getElementById('jenis_kendaraan').value = data.jenis_kendaraan;
            document.getElementById('volume_campuran').value = data.volume_campuran;
            document.getElementById('harga_member').value = data.harga_member;
            document.getElementById('harga_non_member').value = data.harga_non_member;
            calculateDifference();
            document.getElementById('vehicleModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal mengambil data kendaraan: ' + error.message);
        })
        .finally(() => {
            hideLoading();
        });
}

function deleteVehicle(id) {
    if (confirm('Apakah Anda yakin ingin menghapus kendaraan ini?')) {
        showLoading();
        
        fetch(`/api/vehicles/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCSRFToken(),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Gagal menghapus kendaraan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus kendaraan: ' + (error.message || 'Unknown error'));
        })
        .finally(() => {
            hideLoading();
        });
    }
}

// Helper function to get CSRF token
function getCSRFToken() {
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    if (metaTag) {
        return metaTag.getAttribute('content');
    }
    
    // Fallback: try to get from form
    const csrfInput = document.querySelector('input[name="_token"]');
    if (csrfInput) {
        return csrfInput.value;
    }
    
    console.warn('CSRF token not found');
    return '';
}

// Form validation
function validateForm() {
    const id = document.getElementById('id').value.trim();
    const jenisKendaraan = document.getElementById('jenis_kendaraan').value.trim();
    const volumeCampuran = document.getElementById('volume_campuran').value;
    const hargaMember = document.getElementById('harga_member').value;
    const hargaNonMember = document.getElementById('harga_non_member').value;
    
    if (!id) {
        alert('ID kendaraan tidak boleh kosong');
        return false;
    }
    
    if (!jenisKendaraan) {
        alert('Jenis kendaraan tidak boleh kosong');
        return false;
    }
    
    if (!volumeCampuran || volumeCampuran <= 0) {
        alert('Volume campuran harus lebih dari 0');
        return false;
    }
    
    if (!hargaMember || hargaMember <= 0) {
        alert('Harga member harus lebih dari 0');
        return false;
    }
    
    if (!hargaNonMember || hargaNonMember <= 0) {
        alert('Harga non-member harus lebih dari 0');
        return false;
    }
    
    if (parseFloat(hargaNonMember) <= parseFloat(hargaMember)) {
        alert('Harga non-member harus lebih tinggi dari harga member');
        return false;
    }
    
    return true;
}

// Form submission
document.getElementById('vehicleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!validateForm()) {
        return;
    }
    
    const submitBtn = document.getElementById('submitBtn');
    const originalBtnText = submitBtn.textContent;
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.textContent = 'Menyimpan...';
    showLoading();
    
    const formData = new FormData(this);
    const originalId = document.getElementById('originalId').value;
    const isEdit = originalId !== '';
    
    // Convert FormData to JSON with proper data types
    const data = {
        id: formData.get('id').trim(),
        jenis_kendaraan: formData.get('jenis_kendaraan').trim(),
        volume_campuran: parseInt(formData.get('volume_campuran')),
        harga_member: parseInt(formData.get('harga_member')),
        harga_non_member: parseInt(formData.get('harga_non_member'))
    };
    
    // Determine URL and method
    const url = isEdit ? `/api/vehicles/${originalId}` : '/api/vehicles';
    const method = isEdit ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': getCSRFToken(),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || `HTTP error! status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            closeModal();
            location.reload();
        } else {
            throw new Error(data.message || 'Gagal menyimpan data kendaraan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data: ' + error.message);
    })
    .finally(() => {
        // Re-enable button and hide loading
        submitBtn.disabled = false;
        submitBtn.textContent = originalBtnText;
        hideLoading();
    });
});

// Close modal when clicking outside
document.getElementById('vehicleModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Escape key to close modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('vehicleModal').classList.contains('hidden')) {
        closeModal();
    }
});
</script>
@endsection