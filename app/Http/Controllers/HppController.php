<?php
// app/Http/Controllers/HppController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Vehicle;
use App\Models\Component;
use App\Models\ServiceCategory;
use App\Models\HppResult;

class HppController extends Controller
{
    public function index()
    {
        // Ambil semua data yang diperlukan untuk dropdown
        $vehicles = Vehicle::select('id', 'jenis_kendaraan', 'volume_campuran', 'harga_member', 'harga_non_member')
                    ->orderBy('jenis_kendaraan')
                    ->get();
        
        // Ambil service categories dengan relasi component
        $service_categories = ServiceCategory::with('component')
                                ->select('id', 'sumber_pendapatan', 'kategori_pendapatan', 'layanan_hpp', 'proporsi_ml', 'created_at', 'updated_at')
                                ->get();
        
        // Ambil unique sumber_pendapatan dari tabel service_categories
        $sumberPendapatanList = ServiceCategory::whereNotNull('sumber_pendapatan')
                                ->where('sumber_pendapatan', '!=', '')
                                ->distinct()
                                ->orderBy('sumber_pendapatan')
                                ->pluck('sumber_pendapatan')
                                ->values()
                                ->toArray();
        
        // Ambil semua komponen untuk referensi
        $components = Component::select('id', 'name', 'harga_per_ml', 'satuan', 'qty')
                        ->orderBy('name')
                        ->get();
        
        return view('hpp.form', compact('vehicles', 'service_categories', 'components', 'sumberPendapatanList'));
    }

    /**
     * Mendapatkan kategori pendapatan berdasarkan sumber pendapatan
     * Route: POST /hpp/kategori-by-source
     */
    public function getKategoriBySource(Request $request)
    {
        try {
            $request->validate([
                'sumber_pendapatan' => 'required|string'
            ]);
            
            $sumber = $request->sumber_pendapatan;
            
            // Debug: Log input parameter
            \Log::info('Getting kategori for sumber_pendapatan: ' . $sumber);
            
            // Query berdasarkan kolom sumber_pendapatan di tabel service_categories
            $kategori = ServiceCategory::where('sumber_pendapatan', $sumber)
                        ->whereNotNull('kategori_pendapatan')
                        ->where('kategori_pendapatan', '!=', '')
                        ->distinct()
                        ->orderBy('kategori_pendapatan')
                        ->pluck('kategori_pendapatan')
                        ->values()
                        ->toArray();
            
            // Debug: Log result
            \Log::info('Found kategori: ' . json_encode($kategori));
            
            if (empty($kategori)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada kategori pendapatan ditemukan untuk sumber: ' . $sumber,
                    'data' => [],
                    'debug' => [
                        'sumber_pendapatan' => $sumber,
                        'total_service_categories' => ServiceCategory::count(),
                        'available_sumber' => ServiceCategory::distinct()->pluck('sumber_pendapatan')->toArray()
                    ]
                ]);
            }
            
            return response()->json([
                'success' => true,
                'data' => $kategori,
                'message' => count($kategori) . ' kategori ditemukan untuk sumber: ' . $sumber,
                'debug' => [
                    'sumber_pendapatan' => $sumber,
                    'count' => count($kategori)
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getKategoriBySource: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Mendapatkan layanan HPP berdasarkan kategori pendapatan
     * Route: POST /hpp/layanan-by-kategori
     */
    public function getLayananByKategori(Request $request)
    {
        try {
            $request->validate([
                'sumber_pendapatan' => 'required|string',
                'kategori_pendapatan' => 'required|string'
            ]);
            
            $sumber = $request->sumber_pendapatan;
            $kategori = $request->kategori_pendapatan;
            
            // Debug: Log input parameters
            \Log::info('Getting layanan for sumber: ' . $sumber . ', kategori: ' . $kategori);
            
            // Query berdasarkan kolom yang benar di tabel service_categories
            $layanan = ServiceCategory::where('sumber_pendapatan', $sumber)
                        ->where('kategori_pendapatan', $kategori)
                        ->whereNotNull('layanan_hpp')
                        ->where('layanan_hpp', '!=', '')
                        ->get(['id', 'layanan_hpp', 'proporsi_ml'])
                        ->map(function($service) {
                            $component = Component::where('name', $service->layanan_hpp)->first();
                            return [
                                'id' => $service->id,
                                'layanan_hpp' => $service->layanan_hpp,
                                'proporsi_ml' => (float) $service->proporsi_ml,
                                'component_id' => $component ? $component->id : null,
                                'component' => $component ? [
                                    'id' => $component->id,
                                    'name' => $component->name,
                                    'harga_per_ml' => (float) $component->harga_per_ml,
                                    'satuan' => $component->satuan,
                                    'qty' => (float) $component->qty,
                                    'has_stock' => method_exists($component, 'hasStock') ? $component->hasStock() : true
                                ] : null
                            ];
                        });
            
            // Debug: Log result
            \Log::info('Found layanan count: ' . count($layanan));
            
            if ($layanan->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada layanan HPP ditemukan untuk kombinasi sumber: ' . $sumber . ' dan kategori: ' . $kategori,
                    'data' => [],
                    'debug' => [
                        'sumber_pendapatan' => $sumber,
                        'kategori_pendapatan' => $kategori,
                        'total_records' => ServiceCategory::where('sumber_pendapatan', $sumber)->where('kategori_pendapatan', $kategori)->count()
                    ]
                ]);
            }
            
            return response()->json([
                'success' => true,
                'data' => $layanan,
                'message' => count($layanan) . ' layanan ditemukan untuk ' . $sumber . ' - ' . $kategori,
                'debug' => [
                    'sumber_pendapatan' => $sumber,
                    'kategori_pendapatan' => $kategori,
                    'count' => count($layanan)
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getLayananByKategori: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Mendapatkan data service lengkap dengan component info
     * Route: POST /hpp/service-data  
     */
    public function getServiceData(Request $request)
    {
        try {
            $request->validate([
                'sumber_pendapatan' => 'required|string',
                'kategori_pendapatan' => 'required|string', 
                'layanan_hpp' => 'required|string',
                'jenis_kendaraan' => 'required|string'
            ]);
            
            $sumber = $request->sumber_pendapatan;
            $kategori = $request->kategori_pendapatan;
            $layanan = $request->layanan_hpp;
            $jenis_kendaraan = $request->jenis_kendaraan;
            
            // Query berdasarkan kolom yang tepat di tabel service_categories
            $service = ServiceCategory::where('sumber_pendapatan', $sumber)
                        ->where('kategori_pendapatan', $kategori)
                        ->where('layanan_hpp', $layanan)
                        ->first(['id', 'layanan_hpp', 'proporsi_ml']);
            
            // Query vehicles berdasarkan kolom jenis_kendaraan
            $vehicle = Vehicle::where('jenis_kendaraan', $jenis_kendaraan)
                        ->first(['id', 'jenis_kendaraan', 'volume_campuran', 'harga_member', 'harga_non_member']);
            
            if (!$service || !$vehicle) {
                return response()->json([
                    'success' => false,
                    'error' => 'Data service atau vehicle tidak ditemukan',
                    'debug' => [
                        'service_found' => !!$service,
                        'vehicle_found' => !!$vehicle,
                        'search_params' => [
                            'sumber_pendapatan' => $sumber,
                            'kategori_pendapatan' => $kategori,
                            'layanan_hpp' => $layanan,
                            'jenis_kendaraan' => $jenis_kendaraan
                        ]
                    ]
                ], 404);
            }
            
            // Ambil data komponen berdasarkan nama layanan_hpp
            $component = Component::where('name', $service->layanan_hpp)->first();
            
            if (!$component) {
                return response()->json([
                    'success' => false,
                    'error' => 'Component tidak ditemukan untuk layanan ini',
                    'service_id' => $service->id,
                    'component_id' => $service->component_id
                ], 404);
            }
            
            // Hitung HPP berdasarkan formula
            $proporsi_ml = (float) $service->proporsi_ml;
            $proporsi_decimal = $proporsi_ml / 1000;
            
            // Volume campuran dari vehicle atau default
            $volume_campuran = $vehicle->volume_campuran ?? $this->getDefaultVolumeCampuran($jenis_kendaraan);
            $pemakaian = $proporsi_decimal * $volume_campuran;
            
            // HPP calculation
            $harga_per_ml = (float) $component->harga_per_ml;
            $hpp = $pemakaian * $harga_per_ml;
            
            // Margin calculations
            $harga_member = (float) $vehicle->harga_member;
            $harga_non_member = (float) $vehicle->harga_non_member;
            $margin_member = $harga_member - $hpp;
            $margin_non_member = $harga_non_member - $hpp;
            
            // Percentage calculations
            $persen_hpp_member = $harga_member > 0 ? round(($hpp / $harga_member) * 100, 2) : 0;
            $persen_hpp_non_member = $harga_non_member > 0 ? round(($hpp / $harga_non_member) * 100, 2) : 0;

            $result = [
                'service_id' => $service->id,
                'component_id' => $component->id,
                'component_name' => $component->name,
                'component_qty' => (float) $component->qty,
                'component_satuan' => $component->satuan,
                'proporsi_ml' => $proporsi_ml,
                'proporsi_decimal' => $proporsi_decimal,
                'volume_campuran' => $volume_campuran,
                'pemakaian' => round($pemakaian, 2),
                'harga_per_ml' => $harga_per_ml,
                'hpp' => round($hpp, 2),
                'margin_member' => round($margin_member, 2),
                'margin_non_member' => round($margin_non_member, 2),
                'persen_hpp_member' => $persen_hpp_member,
                'persen_hpp_non_member' => $persen_hpp_non_member,
                'harga_member' => $harga_member,
                'harga_non_member' => $harga_non_member,
                'component_has_stock' => method_exists($component, 'hasStock') ? $component->hasStock() : true
            ];
            
            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'Data berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getDefaultVolumeCampuran($jenis_kendaraan)
    {
        $volumes = [
            'S' => 750,
            'M' => 1000, 
            'L' => 1250,
            'XL' => 1500,
            'Sport & Luxury' => 1500
        ];
        
        return $volumes[$jenis_kendaraan] ?? 1000;
    }

    /**
     * Simpan data HPP hasil perhitungan
     */
    public function store(Request $request)
    {
        try {
            // Extract from nested calculated object if present
            $calculated = $request->input('calculated', []);
            
            $dataToValidate = [
                'sumber_pendapatan' => $request->sumber_pendapatan,
                'jenis_kendaraan' => $request->jenis_kendaraan,
                'kategori_pendapatan' => $request->kategori_pendapatan,
                'layanan_hpp' => $request->layanan_hpp,
                'proporsi_ml' => $request->proporsi_ml,
                'proporsi_decimal' => $request->proporsi_decimal ?? ($calculated['proporsi_decimal'] ?? null),
                'pemakaian' => $request->pemakaian ?? ($calculated['pemakaian'] ?? null),
                'harga_per_ml' => $request->harga_per_ml ?? ($calculated['harga_per_ml'] ?? null),
                'hpp' => $request->hpp ?? ($calculated['hpp'] ?? null),
                'margin_member' => $request->margin_member ?? ($calculated['margin_member'] ?? null),
                'margin_non_member' => $request->margin_non_member ?? ($calculated['margin_non_member'] ?? null),
                'persen_hpp_member' => $request->persen_hpp_member ?? ($calculated['persen_hpp_member'] ?? null),
                'persen_hpp_non_member' => $request->persen_hpp_non_member ?? ($calculated['persen_hpp_non_member'] ?? null),
            ];

            $validator = Validator::make($dataToValidate, [
                'sumber_pendapatan' => 'required|string|max:255',
                'jenis_kendaraan' => 'required|string|max:255',
                'kategori_pendapatan' => 'required|string|max:255',
                'layanan_hpp' => 'required|string|max:255',
                'proporsi_ml' => 'required|numeric|min:0',
                'proporsi_decimal' => 'required|numeric|min:0',
                'pemakaian' => 'required|numeric|min:0',
                'harga_per_ml' => 'required|numeric|min:0',
                'hpp' => 'required|numeric|min:0',
                'margin_member' => 'required|numeric',
                'margin_non_member' => 'required|numeric',
                'persen_hpp_member' => 'required|numeric|min:0|max:100',
                'persen_hpp_non_member' => 'required|numeric|min:0|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all())
                ], 422);
            }

            // Validasi vehicle exists
            $vehicle = Vehicle::where('jenis_kendaraan', $dataToValidate['jenis_kendaraan'])->first();
            
            // Validasi service category exists
            $serviceCategory = ServiceCategory::where('sumber_pendapatan', $dataToValidate['sumber_pendapatan'])
                                ->where('kategori_pendapatan', $dataToValidate['kategori_pendapatan'])
                                ->where('layanan_hpp', $dataToValidate['layanan_hpp'])
                                ->first();

            if (!$vehicle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle dengan jenis kendaraan "' . $dataToValidate['jenis_kendaraan'] . '" tidak ditemukan!'
                ], 404);
            }
            
            if (!$serviceCategory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service category tidak ditemukan untuk kombinasi data yang dipilih!'
                ], 404);
            }

            // Simpan ke tabel hpp_results
            $hppResult = HppResult::create([
                'title' => $dataToValidate['sumber_pendapatan'] . ' - ' . $dataToValidate['jenis_kendaraan'] . ' - ' . $dataToValidate['layanan_hpp'],
                'jenis_kendaraan' => $dataToValidate['jenis_kendaraan'],
                'sumber_pendapatan' => $dataToValidate['sumber_pendapatan'],
                'kategori_pendapatan' => $dataToValidate['kategori_pendapatan'],
                'layanan_hpp' => $dataToValidate['layanan_hpp'],
                'proporsi_ml' => (float) $dataToValidate['proporsi_ml'],
                'proporsi_decimal' => (float) $dataToValidate['proporsi_decimal'],
                'pemakaian' => (float) $dataToValidate['pemakaian'],
                'harga_per_ml' => (float) $dataToValidate['harga_per_ml'],
                'hpp' => (float) $dataToValidate['hpp'],
                'margin_member' => (float) $dataToValidate['margin_member'],
                'margin_non_member' => (float) $dataToValidate['margin_non_member'],
                'persen_hpp_member' => (float) $dataToValidate['persen_hpp_member'],
                'persen_hpp_non_member' => (float) $dataToValidate['persen_hpp_non_member']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data HPP berhasil disimpan!',
                'data' => $hppResult
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method tambahan untuk debugging dan testing
    public function getVehicles()
    {
        $vehicles = Vehicle::select('id', 'jenis_kendaraan', 'volume_campuran', 'harga_member', 'harga_non_member')
                    ->orderBy('jenis_kendaraan')
                    ->get();
        
        return response()->json([
            'success' => true,
            'data' => $vehicles
        ]);
    }

    public function getCategories()
    {
        $categories = ServiceCategory::with(['component' => function($query) {
                        $query->select('id', 'name', 'harga_per_ml', 'satuan', 'qty');
                    }])
                    ->select('id', 'sumber_pendapatan', 'kategori_pendapatan', 'layanan_hpp', 'proporsi_ml', 'component_id')
                    ->get()
                    ->groupBy('sumber_pendapatan');
        
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function getHppHistory(Request $request)
    {
        $query = HppResult::query();
        
        // Filter berdasarkan parameter
        if ($request->filled('sumber_pendapatan')) {
            $query->where('sumber_pendapatan', $request->sumber_pendapatan);
        }
        
        if ($request->filled('jenis_kendaraan')) {
            $query->where('jenis_kendaraan', $request->jenis_kendaraan);
        }
        
        if ($request->filled('kategori_pendapatan')) {
            $query->where('kategori_pendapatan', $request->kategori_pendapatan);
        }
        
        if ($request->filled('layanan_hpp')) {
            $query->where('layanan_hpp', $request->layanan_hpp);
        }
        
        $history = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    // Method untuk debugging - cek data di database
    /**
     * Fetch Komponen berdasarkan Kategori (bahan_baku / tenaga_kerja / overhead)
     */
    public function getKomponenByKategori(Request $request)
    {
        $kategori = $request->query('kategori');
        $komponens = Component::when($kategori, function ($q) use ($kategori) {
            return $q->where('kategori', $kategori);
        })->get();

        return response()->json([
            'status' => 'success',
            'data' => $komponens
        ]);
    }

    /**
     * Fetch Detail Layanan beserta Komponen HPP-nya (Auto-fill Transaksi/HPP)
     */
    public function getLayananDetail($id)
    {
        $layanan = ServiceCategory::with('komponens')->findOrFail($id);
        
        $totalHpp = $layanan->komponens->sum(function($item) {
            return $item->pivot->subtotal_biaya ?? ($item->harga_per_ml * $item->pivot->jumlah_pemakaian);
        });

        return response()->json([
            'status' => 'success',
            'layanan' => $layanan,
            'total_hpp' => $totalHpp,
            'komponens' => $layanan->komponens
        ]);
    }
}