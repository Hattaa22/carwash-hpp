{{-- resources/views/admin/components.blade.php --}}
@extends('layouts.app')

@section('title', 'Kelola Komponen HPP')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Kelola Komponen HPP</h1>
                    <p class="text-gray-600">Manage harga dan data komponen untuk perhitungan HPP</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Komponen
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Komponen</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $components->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Nilai Komponen</p>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($components->sum('harga'), 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Rata-rata Harga/Unit</p>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($components->avg('harga_per_satuan'), 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Cari komponen..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="md:w-48">
                    <select id="categoryFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Kategori</option>
                        <option value="Car Wash">Car Wash</option>
                        <option value="Treatment">Treatment</option>
                    </select>
                </div>
                <div class="md:w-48">
                    <select id="satuanFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Satuan</option>
                        <option value="ml">ml</option>
                        <option value="pcs">pcs</option>
                        <option value="lembar">lembar</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Components Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Komponen</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">name Komponen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Beli</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga/Satuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="componentsTable" class="bg-white divide-y divide-gray-200">
                        @foreach($components as $component)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-semibold">
                                        {{ substr($component->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $component->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $component->kategori == 'Car Wash' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $component->kategori }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp {{ number_format($component->harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($component->qty, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $component->satuan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp {{ number_format($component->harga_per_satuan, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="editComponent({{ $component->id }})" class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="deleteComponent({{ $component->id }})" class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $components->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Component Modal -->
<div id="componentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Tambah Komponen</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="componentForm" class="space-y-4">
                @csrf
                <input type="hidden" id="componentId" name="id">
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">nama Komponen</label>
                    <input type="text" id="name" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select id="kategori" name="kategori" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Kategori</option>
                        <option value="Car Wash">Car Wash</option>
                        <option value="Treatment">Treatment</option>
                    </select>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="harga" class="block text-sm font-medium text-gray-700 mb-1">Harga Beli</label>
                        <input type="number" id="harga" name="harga" required min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="qty" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                        <input type="number" id="qty" name="qty" required min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <div>
                    <label for="satuan" class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                    <select id="satuan" name="satuan" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Satuan</option>
                        <option value="ml">ml</option>
                        <option value="pcs">pcs</option>
                        <option value="lembar">lembar</option>
                    </select>
                </div>
                
                <div>
                    <label for="harga_per_satuan" class="block text-sm font-medium text-gray-700 mb-1">Harga per Satuan</label>
                    <input type="number" id="harga_per_satuan" name="harga_per_satuan" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Otomatis dihitung dari harga beli ÷ quantity</p>
                </div>
                
                <div class="flex space-x-3 pt-4">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition-colors duration-200">
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

<script>
// Auto calculate harga per satuan
document.getElementById('harga').addEventListener('input', calculateHargaPerSatuan);
document.getElementById('qty').addEventListener('input', calculateHargaPerSatuan);

function calculateHargaPerSatuan() {
    const harga = parseFloat(document.getElementById('harga').value) || 0;
    const qty = parseFloat(document.getElementById('qty').value) || 0;
    const hargaPerSatuan = qty > 0 ? harga / qty : 0;
    document.getElementById('harga_per_satuan').value = hargaPerSatuan.toFixed(2);
}

// Modal functions
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Komponen';
    document.getElementById('componentForm').reset();
    document.getElementById('componentId').value = '';
    document.getElementById('componentModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('componentModal').classList.add('hidden');
}

function editComponent(id) {
    // Fetch component data and populate form - fixed URL to match route
    fetch(`{{ route('admin.components.show', '') }}/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalTitle').textContent = 'Edit Komponen';
            document.getElementById('componentId').value = data.id;
            document.getElementById('name').value = data.name;
            document.getElementById('kategori').value = data.kategori;
            document.getElementById('harga').value = data.harga;
            document.getElementById('qty').value = data.qty;
            document.getElementById('satuan').value = data.satuan;
            document.getElementById('harga_per_satuan').value = data.harga_per_satuan;
            document.getElementById('componentModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal mengambil data komponen');
        });
}

function deleteComponent(id) {
    if (confirm('Apakah Anda yakin ingin menghapus komponen ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.components.destroy', '') }}/${id}`;
        
        // Add CSRF token
        const csrfField = document.createElement('input');
        csrfField.type = 'hidden';
        csrfField.name = '_token';
        csrfField.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfField);
        
        // Add method override for DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Form submission
document.getElementById('componentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const id = document.getElementById('componentId').value;
    
    let url, method;
    if (id) {
        // Update existing component
        url = `{{ route('admin.components.update', '') }}/${id}`;
        method = 'POST';
        // Add method override for PUT
        formData.append('_method', 'PUT');
    } else {
        // Create new component
        url = `{{ route('admin.components.store') }}`;
        method = 'POST';
    }
    
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => {
        if (response.ok) {
            closeModal();
            location.reload();
        } else {
            throw new Error('Network response was not ok');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal menyimpan data komponen');
    });
});

// Search and filter functionality
document.getElementById('searchInput').addEventListener('input', filterTable);
document.getElementById('categoryFilter').addEventListener('change', filterTable);
document.getElementById('satuanFilter').addEventListener('change', filterTable);

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value;
    const satuanFilter = document.getElementById('satuanFilter').value;
    const rows = document.querySelectorAll('#componentsTable tr');
    
    rows.forEach(row => {
        const name = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
        const kategori = row.querySelector('td:nth-child(2)').textContent.trim();
        const satuan = row.querySelector('td:nth-child(5)').textContent.trim();
        
        const matchesSearch = name.includes(searchTerm);
        const matchesCategory = !categoryFilter || kategori === categoryFilter;
        const matchesSatuan = !satuanFilter || satuan === satuanFilter;
        
        if (matchesSearch && matchesCategory && matchesSatuan) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endsection