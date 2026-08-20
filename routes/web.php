<?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HppController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ComponentController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\ServiceCategoryController;

Route::get('/', function () {
    return redirect('/dashboard');
});

// Dashboard Routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/dashboard/export', [DashboardController::class, 'export'])->name('dashboard.export');

Route::prefix('hpp')->name('hpp.')->middleware('hpp.middleware')->group(function () {
    Route::get('/', [HppController::class, 'index'])->name('index');
    Route::post('/kategori-by-source', [HppController::class, 'getKategoriBySource'])->name('kategori-by-source');
    Route::post('/layanan-by-kategori', [HppController::class, 'getLayananByKategori'])->name('layanan-by-kategori');
    Route::post('/service-data', [HppController::class, 'getServiceData'])->name('service-data');
    Route::post('/store', [HppController::class, 'store'])->name('store');
});

// API Routes for dynamic loading
Route::get('/api/layanan', [HppController::class, 'getLayanan'])->name('api.layanan');
Route::get('/api/component-price', [HppController::class, 'getComponentPrice'])->name('api.component.price');
Route::post('/api/calculate', [HppController::class, 'calculate'])->name('api.calculate');

Route::prefix('admin')->as('admin.')->group(function () {
    Route::get('/components', [ComponentController::class, 'index'])->name('components');
    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles');
    Route::get('/categories', [ServiceCategoryController::class, 'index'])->name('categories');
});
        Route::get('/components', [ComponentController::class, 'index'])->name('admin.components');
        Route::get('/create', [ComponentController::class, 'create'])->name('admin.components.create');
        Route::post('/component/store', [ComponentController::class, 'store'])->name('admin.components.store');
        Route::get('/{component}', [ComponentController::class, 'show'])->name('admin.components.show');
        Route::get('/{component}/edit', [ComponentController::class, 'edit'])->name('admin.components.edit');
        Route::put('/{component}', [ComponentController::class, 'update'])->name('admin.components.update');
        Route::delete('/destroy', [ComponentController::class, 'destroy'])->name('admin.components.destroy');
        
        // Bulk operations
        Route::post('/bulk-update', [ComponentController::class, 'bulkUpdate'])->name('bulk-update');
        Route::post('/import', [ComponentController::class, 'importComponents'])->name('import');
        
        // API endpoints
        Route::get('/api/by-category', [ComponentController::class, 'getByCategory'])->name('api.by-category');

        Route::get('/', [VehicleController::class, 'index'])->name('admin.vehicles');
        Route::get('/create', [VehicleController::class, 'create'])->name('admin.vehicles.create');
        Route::post('/', [VehicleController::class, 'store'])->name('admin.vehicles.store');
        Route::get('/{vehicle}', [VehicleController::class, 'show'])->name('admin.vehicles.show');
        Route::get('/{vehicle}/edit', [VehicleController::class, 'edit'])->name('admin.vehicles.edit');
        Route::put('/{vehicle}', [VehicleController::class, 'update'])->name('admin.vehicles.update');
        Route::delete('/{vehicle}', [VehicleController::class, 'destroy'])->name('admin.vehicles.destroy');
        
        // Bulk operations
        Route::post('/bulk-update-prices', [VehicleController::class, 'bulkUpdatePrices'])->name('bulk-update-prices');
        Route::post('/import-default', [VehicleController::class, 'importDefaultData'])->name('import-default');
        
        // API endpoints
        Route::get('/api/pricing', [VehicleController::class, 'getPricing'])->name('api.pricing');
        Route::get('/api/options', [VehicleController::class, 'getVehicleOptions'])->name('api.options');

        Route::get('/', [ServiceCategoryController::class, 'index'])->name('admin.categories');
        Route::get('/create', [ServiceCategoryController::class, 'create'])->name('admin.categories.create');
        Route::post('/store', [ServiceCategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('/{category}', [ServiceCategoryController::class, 'show'])->name('admin.categories.show');
        Route::get('/{category}/edit', [ServiceCategoryController::class, 'edit'])->name('admin.categories..edit');
        Route::put('/{category}', [ServiceCategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/{category}', [ServiceCategoryController::class, 'destroy'])->name('admin.categories.destroy');
        
        // Import and structure
        Route::post('/import-default', [ServiceCategoryController::class, 'importDefaultData'])->name('import-default');
        
        // API endpoints
        Route::get('/api/by-revenue-source', [ServiceCategoryController::class, 'getByRevenueSource'])->name('api.by-revenue-source');
        Route::get('/api/subcategories', [ServiceCategoryController::class, 'getSubcategories'])->name('api.subcategories');
        Route::get('/api/components', [ServiceCategoryController::class, 'getComponents'])->name('api.components');
        Route::get('/api/structure', [ServiceCategoryController::class, 'getStructure'])->name('api.structure');
