<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ServiceCategoryController extends Controller
{
    /**
     * Display a listing of service categories
     */
    public function index()
    {
        $categories = ServiceCategory::with('component')
            ->orderBy('sumber_pendapatan')
            ->paginate(10);
            
        return view('admin.categories', compact('categories'));
    }

    /**
     * Show the form for creating a new service category
     */
    public function create()
    {
        $components = Component::orderBy('name')->get();
        return view('admin.categories.create', compact('components'));
    }

    /**
     * Store a newly created service category
     */
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'source' => 'required|string|max:100',
        'category_name' => 'required|string|max:100',
        'components' => 'required|string'
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $componentNames = array_map('trim', explode(',', $request->components));

    try {
        foreach ($componentNames as $componentName) {
            $component = Component::where('name', $componentName)->first();

            if (!$component) {
                return redirect()->back()
                    ->with('error', "Komponen '$componentName' tidak ditemukan")
                    ->withInput();
            }

            ServiceCategory::create([
                'sumber_pendapatan' => $request->source,
                'kategori_pendapatan' => $request->category_name,
                'layanan_hpp' => $component->name,
                'proporsi_ml' => 0 // atau default lain jika ada
            ]);
        }

        return redirect()->route('admin.categories')
            ->with('success', 'Kategori layanan berhasil disimpan dengan semua komponennya');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Terjadi kesalahan saat menyimpan data')
            ->withInput();
    }
}


    /**
     * Display the specified service category
     */
    public function show(ServiceCategory $category)
    {
        $category->load('component');
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified service category
     */
    public function edit(ServiceCategory $category)
    {
        $category->load('component');
        $components = Component::orderBy('name')->get();
        return view('admin.categories.edit', compact('category', 'components'));
    }

    /**
     * Update the specified service category
     */
    public function update(Request $request, ServiceCategory $category)
    {
        $validator = Validator::make($request->all(), [
            'sumber_pendapatan' => 'required|string|max:100',
            'kategori_pendapatan' => 'required|string|max:100',
            'layanan_hpp' => 'required|string|max:100',
            'proporsi_ml' => 'required|numeric|min:0',
            'component_name' => 'required|exists:components,name'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $category->update([
                'sumber_pendapatan' => $request->sumber_pendapatan,
                'kategori_pendapatan' => $request->kategori_pendapatan,
                'layanan_hpp' => $request->component_name, // This links to component via name
                'proporsi_ml' => $request->proporsi_ml
            ]);

            return redirect()->route('admin.categories')
                ->with('success', 'Kategori layanan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data')
                ->withInput();
        }
    }

    /**
     * Remove the specified service category
     */
    public function destroy(ServiceCategory $category)
    {
        try {
            $category->delete();
            
            return redirect()->route('admin.categories')
                ->with('success', 'Kategori layanan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.categories')
                ->with('error', 'Kategori layanan tidak bisa dihapus karena masih digunakan');
        }
    }

    /**
     * Get categories by revenue source for AJAX
     */
    public function getBySumberPendapatan(Request $request)
    {
        $sumberPendapatan = $request->get('sumber_pendapatan');
        $categories = ServiceCategory::where('sumber_pendapatan', $sumberPendapatan)
            ->distinct()
            ->pluck('kategori_pendapatan');

        return response()->json($categories);
    }

    /**
     * Get services by category for AJAX
     */
    public function getServices(Request $request)
    {
        $sumberPendapatan = $request->get('sumber_pendapatan');
        $kategoriPendapatan = $request->get('kategori_pendapatan');

        $services = ServiceCategory::where('sumber_pendapatan', $sumberPendapatan)
            ->where('kategori_pendapatan', $kategoriPendapatan)
            ->with('component:id,name,harga_per_ml,satuan')
            ->get(['id', 'layanan_hpp', 'proporsi_ml']);

        return response()->json($services);
    }

    /**
     * Get component by service category
     */
    public function getComponent(Request $request)
    {
        $categoryId = $request->get('category_id');
        $category = ServiceCategory::with('component:id,name,harga_per_ml,satuan')
            ->find($categoryId);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        return response()->json([
            'category' => $category,
            'component' => $category->component,
            'cost_per_service' => $category->cost_per_service,
            'formatted_cost' => $category->formatted_cost_per_service
        ]);
    }

    /**
     * Import default service categories
     */
    public function importDefaultData()
    {
        $masterData = [
            [
                'sumber_pendapatan' => 'Pendapatan',
                'kategori_pendapatan' => 'Car Wash',
                'layanan_hpp' => 'Touchless',
                'proporsi_ml' => 50.00
            ],
            [
                'sumber_pendapatan' => 'Pendapatan',
                'kategori_pendapatan' => 'Treatment',
                'layanan_hpp' => 'Degreaser',
                'proporsi_ml' => 30.00
            ],
            // Add more default data as needed
        ];

        DB::beginTransaction();
        try {
            $imported = 0;
            $errors = [];

            foreach ($masterData as $data) {
                // Check if component exists
                $component = Component::where('name', $data['layanan_hpp'])->first();
                
                if (!$component) {
                    $errors[] = "Component '{$data['layanan_hpp']}' not found";
                    continue;
                }

                ServiceCategory::updateOrCreate([
                    'sumber_pendapatan' => $data['sumber_pendapatan'],
                    'kategori_pendapatan' => $data['kategori_pendapatan'],
                    'layanan_hpp' => $data['layanan_hpp']
                ], [
                    'proporsi_ml' => $data['proporsi_ml']
                ]);

                $imported++;
            }

            DB::commit();
            return response()->json([
                'message' => "Berhasil import {$imported} kategori layanan",
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'Terjadi kesalahan saat import data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get service category structure for dropdown
     */
    public function getStructure()
    {
        $structure = ServiceCategory::select('sumber_pendapatan', 'kategori_pendapatan', 'layanan_hpp', 'id', 'proporsi_ml')
            ->with('component:name,harga_per_ml,satuan')
            ->orderBy('sumber_pendapatan')
            ->orderBy('kategori_pendapatan')
            ->orderBy('layanan_hpp')
            ->get()
            ->groupBy('sumber_pendapatan')
            ->map(function ($revenueGroup) {
                return $revenueGroup->groupBy('kategori_pendapatan')->map(function ($categoryGroup) {
                    return $categoryGroup->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'layanan_hpp' => $item->layanan_hpp,
                            'proporsi_ml' => $item->proporsi_ml,
                            'component' => $item->component,
                            'cost_per_service' => $item->cost_per_service,
                            'formatted_cost' => $item->formatted_cost_per_service
                        ];
                    });
                });
            });

        return response()->json($structure);
    }

    /**
     * Calculate total cost for multiple services
     */
    public function calculateTotalCost(Request $request)
    {
        $serviceIds = $request->get('service_ids', []);
        
        if (empty($serviceIds)) {
            return response()->json(['error' => 'No services selected'], 400);
        }

        $services = ServiceCategory::with('component')
            ->whereIn('id', $serviceIds)
            ->get();

        $totalCost = $services->sum('cost_per_service');
        $serviceDetails = $services->map(function ($service) {
            return [
                'id' => $service->id,
                'layanan_hpp' => $service->layanan_hpp,
                'proporsi_ml' => $service->proporsi_ml,
                'cost_per_service' => $service->cost_per_service,
                'formatted_cost' => $service->formatted_cost_per_service,
                'component' => $service->component ? [
                    'name' => $service->component->name,
                    'harga_per_ml' => $service->component->harga_per_ml,
                    'satuan' => $service->component->satuan
                ] : null
            ];
        });

        return response()->json([
            'services' => $serviceDetails,
            'total_cost' => $totalCost,
            'formatted_total_cost' => 'Rp ' . number_format($totalCost, 0, ',', '.'),
            'service_count' => $services->count()
        ]);
    }
}