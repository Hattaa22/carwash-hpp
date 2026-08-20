<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HppResult;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Validasi input
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'vehicle_type' => 'nullable|exists:vehicles,jenis_kendaraan',
            'category' => 'nullable|string'
        ]);

        $query = HppResult::query()->with(['vehicle', 'serviceCategory', 'component']);

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter berdasarkan jenis kendaraan
        if ($request->filled('vehicle_type')) {
            $query->where('jenis_kendaraan', $request->vehicle_type);
        }

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('kategori_pendapatan', $request->category);
        }

        // Clone query untuk statistics sebelum pagination
        $baseQuery = clone $query;
        
        $results = $query->latest()->paginate(10);
        
        // Data untuk grafik - lebih efisien dengan DB aggregation
        $chartData = $this->getChartData($baseQuery);
        
        // Summary statistics dari baseQuery
        $statistics = $this->getStatistics($baseQuery);

        // Data untuk dropdown filter
        $vehicleTypes = Vehicle::pluck('jenis_kendaraan', 'jenis_kendaraan');
        $categories = HppResult::distinct()->pluck('kategori_pendapatan', 'kategori_pendapatan');

        return view('dashboard.index', compact(
            'results', 
            'chartData', 
            'statistics',
            'vehicleTypes',
            'categories'
        ));
    }

    private function getChartData($query)
    {
        try {
            return $query->select(
                'jenis_kendaraan',
                DB::raw('AVG(hpp) as avg_hpp'),
                DB::raw('AVG(margin_member) as avg_margin_member'),
                DB::raw('AVG(margin_non_member) as avg_margin_non_member'),
                DB::raw('COUNT(*) as total_records')
            )
            ->groupBy('jenis_kendaraan')
            ->get()
            ->map(function ($item) {
                return [
                    'vehicle' => $item->jenis_kendaraan,
                    'avg_hpp' => round($item->avg_hpp, 2),
                    'avg_margin_member' => round($item->avg_margin_member, 2),
                    'avg_margin_non_member' => round($item->avg_margin_non_member, 2),
                    'total_records' => $item->total_records
                ];
            });
        } catch (\Exception $e) {
            // Log error dan return empty collection
            ('Error getting chart data: ' . $e->getMessage());
            return collect([]);
        }
    }

    private function getStatistics($query)
    {
        try {
            $stats = $query->select(
                DB::raw('COUNT(*) as total_results'),
                DB::raw('AVG(hpp) as avg_hpp'),
                DB::raw('AVG(margin_member) as avg_margin_member'),
                DB::raw('AVG(margin_non_member) as avg_margin_non_member'),
                DB::raw('MIN(hpp) as min_hpp'),
                DB::raw('MAX(hpp) as max_hpp')
            )->first();

            return [
                'total_results' => $stats->total_results ?? 0,
                'avg_hpp' => round($stats->avg_hpp ?? 0, 2),
                'avg_margin_member' => round($stats->avg_margin_member ?? 0, 2),
                'avg_margin_non_member' => round($stats->avg_margin_non_member ?? 0, 2),
                'min_hpp' => round($stats->min_hpp ?? 0, 2),
                'max_hpp' => round($stats->max_hpp ?? 0, 2)
            ];
        } catch (\Exception $e) {
            ('Error getting statistics: ' . $e->getMessage());
            return [
                'total_results' => 0,
                'avg_hpp' => 0,
                'avg_margin_member' => 0,
                'avg_margin_non_member' => 0,
                'min_hpp' => 0,
                'max_hpp' => 0
            ];
        }
    }

    public function export(Request $request)
    {
        $request->validate([
            'format' => 'required|in:excel,csv,pdf',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'vehicle_type' => 'nullable|exists:vehicles,jenis_kendaraan',
            'category' => 'nullable|string'
        ]);

        // Apply same filters as index method
        $query = HppResult::query()->with(['vehicle', 'serviceCategory', 'component']);
        
        // Apply filters...
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('vehicle_type')) {
            $query->where('jenis_kendaraan', $request->vehicle_type);
        }

        if ($request->filled('category')) {
            $query->where('kategori_pendapatan', $request->category);
        }

        $data = $query->get();

        // Implementation for Excel export using PhpSpreadsheet
        // or return download response
        return response()->json([
            'message' => 'Export feature ready for implementation',
            'total_records' => $data->count(),
            'format' => $request->format
        ]);
    }

    /**
     * API endpoint untuk chart data (AJAX)
     */
    public function getChartDataAjax(Request $request)
    {
        $query = HppResult::query();
        
        // Apply same filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('vehicle_type')) {
            $query->where('jenis_kendaraan', $request->vehicle_type);
        }

        if ($request->filled('category')) {
            $query->where('kategori_pendapatan', $request->category);
        }

        $chartData = $this->getChartData($query);
        
        return response()->json([
            'success' => true,
            'data' => $chartData
        ]);
    }
}