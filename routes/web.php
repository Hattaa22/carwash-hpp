<?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HppController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ComponentController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\ServiceCategoryController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard Routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::match(['get', 'post'], '/dashboard/export', [DashboardController::class, 'export'])->name('dashboard.export');

// HPP Kalkulator Routes
Route::prefix('hpp')->name('hpp.')->middleware('hpp.middleware')->group(function () {
    Route::get('/', [HppController::class, 'index'])->name('index');
    Route::post('/kategori-by-source', [HppController::class, 'getKategoriBySource'])->name('kategori-by-source');
    Route::post('/layanan-by-kategori', [HppController::class, 'getLayananByKategori'])->name('layanan-by-kategori');
    Route::post('/service-data', [HppController::class, 'getServiceData'])->name('service-data');
    Route::post('/store', [HppController::class, 'store'])->name('store');
});

// API Endpoints
Route::get('/api/layanan', [HppController::class, 'getLayanan'])->name('api.layanan');
Route::get('/api/layanan/{id}', [HppController::class, 'getLayananDetail'])->name('api.layanan.detail');
Route::get('/api/komponen-by-kategori', [HppController::class, 'getKomponenByKategori'])->name('api.komponen.kategori');
Route::get('/api/component-price', [HppController::class, 'getComponentPrice'])->name('api.component.price');
Route::post('/api/calculate', [HppController::class, 'calculate'])->name('api.calculate');

// Admin Routes Group
Route::prefix('admin')->name('admin.')->group(function () {
    // Components
    Route::get('/components', [ComponentController::class, 'index'])->name('components');
    Route::post('/components/store', [ComponentController::class, 'store'])->name('components.store');
    Route::get('/components/{component}', [ComponentController::class, 'show'])->name('components.show');
    Route::put('/components/{component}', [ComponentController::class, 'update'])->name('components.update');
    Route::delete('/components/{component}', [ComponentController::class, 'destroy'])->name('components.destroy');
    
    // Vehicles
    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles');
    Route::post('/vehicles/store', [VehicleController::class, 'store'])->name('vehicles.store');
    Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');
    Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');
    Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');
    
    // Service Categories
    Route::get('/categories', [ServiceCategoryController::class, 'index'])->name('categories');
    Route::post('/categories/store', [ServiceCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}', [ServiceCategoryController::class, 'show'])->name('categories.show');
    Route::put('/categories/{category}', [ServiceCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [ServiceCategoryController::class, 'destroy'])->name('categories.destroy');
});
