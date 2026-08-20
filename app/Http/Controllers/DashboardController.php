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

        $query = HppResult::query()->with(['vehicle', 'serviceCategory']);

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
            $items = (clone $query)->get();

            $memberPoints = [];
            $nonMemberPoints = [];
            $vehicleDistribution = [];

            foreach ($items as $item) {
                if ($item->hpp > 0) {
                    $memberPoints[] = [
                        'x' => (float)$item->hpp,
                        'y' => (float)$item->persen_hpp_member
                    ];
                    $nonMemberPoints[] = [
                        'x' => (float)$item->hpp,
                        'y' => (float)$item->persen_hpp_non_member
                    ];
                }

                $vk = $item->jenis_kendaraan ?? 'Lainnya';
                if (!isset($vehicleDistribution[$vk])) {
                    $vehicleDistribution[$vk] = 0;
                }
                $vehicleDistribution[$vk]++;
            }

            return [
                'hppMargin' => [
                    'member' => $memberPoints,
                    'nonMember' => $nonMemberPoints
                ],
                'vehicleDistribution' => $vehicleDistribution
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting chart data: ' . $e->getMessage());
            return [
                'hppMargin' => ['member' => [], 'nonMember' => []],
                'vehicleDistribution' => []
            ];
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
            \Log::error('Error getting statistics: ' . $e->getMessage());
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
            'format' => 'nullable|in:excel,csv,pdf',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'vehicle_type' => 'nullable|exists:vehicles,jenis_kendaraan',
            'category' => 'nullable|string'
        ]);

        $query = HppResult::query()->with(['vehicle', 'serviceCategory']);
        
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

        $data = $query->latest()->get();

        $fileName = 'Laporan_HPP_Carwash_' . date('Ymd_His') . '.xls';

        $headers = [
            "Content-type" => "application/vnd.ms-excel; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use($data) {
            $output = fopen('php://output', 'w');
            
            // Native Excel-optimized HTML layout with mso-number-format
            $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Laporan HPP</x:Name>
                    <x:WorksheetOptions>
                        <x:DisplayGridlines/>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>
    <![endif]-->
    <style>
        body { font-family: Arial, sans-serif; font-size: 11pt; }
        .title-main { font-size: 14pt; font-weight: bold; color: #1e3a8a; }
        .meta-info { font-size: 10pt; color: #475569; }
        th { background-color: #2563eb; color: #ffffff; font-weight: bold; font-size: 11pt; border: 1px solid #1d4ed8; text-align: center; vertical-align: middle; height: 32px; }
        td { border: 1px solid #cbd5e1; padding: 6px 10px; font-size: 10pt; vertical-align: middle; }
        .bg-even { background-color: #f8fafc; }
        .num-fmt { mso-number-format: "\#\,\#\#0"; text-align: right; }
        .pct-fmt { mso-number-format: "0\.0%"; text-align: center; }
        .txt-fmt { mso-number-format: "\@"; text-align: center; }
        .total-row td { background-color: #e2e8f0; font-weight: bold; border-top: 2px solid #0f172a; border-bottom: 2px solid #0f172a; font-size: 11pt; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="12" class="title-main" style="border:none; height:30px;">LAPORAN HASIL KALKULASI HPP CARWASH & TREATMENT</td>
        </tr>
        <tr>
            <td colspan="12" class="meta-info" style="border:none; height:20px;">Tanggal Ekspor: ' . date('d/m/Y H:i:s') . ' | Total: ' . $data->count() . ' Records Data</td>
        </tr>
        <tr><td colspan="12" style="border:none; height:10px;"></td></tr>
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th style="width: 120px;">ID Transaksi</th>
                <th style="width: 140px;">Tanggal Perhitungan</th>
                <th style="width: 140px;">Sumber Pendapatan</th>
                <th style="width: 140px;">Kategori Layanan</th>
                <th style="width: 200px;">Nama Layanan</th>
                <th style="width: 120px;">Tipe Kendaraan</th>
                <th style="width: 140px;">HPP (Rp)</th>
                <th style="width: 150px;">Margin Member (Rp)</th>
                <th style="width: 130px;">HPP Member (%)</th>
                <th style="width: 160px;">Margin Non-Member (Rp)</th>
                <th style="width: 140px;">HPP Non-Member (%)</th>
            </tr>
        </thead>
        <tbody>';

            $no = 1;
            $totalHpp = 0;
            $totalMarginMember = 0;
            $totalMarginNonMember = 0;

            foreach ($data as $row) {
                $totalHpp += $row->hpp;
                $totalMarginMember += $row->margin_member;
                $totalMarginNonMember += $row->margin_non_member;

                $rowClass = ($no % 2 == 0) ? 'class="bg-even"' : '';

                $html .= '
            <tr ' . $rowClass . '>
                <td class="txt-fmt">' . $no++ . '</td>
                <td class="txt-fmt"><b>HPP-' . str_pad($row->id, 5, '0', STR_PAD_LEFT) . '</b></td>
                <td class="txt-fmt">' . Carbon::parse($row->created_at)->format('d/m/Y H:i') . '</td>
                <td style="text-align: left;">' . htmlspecialchars($row->sumber_pendapatan ?? '-') . '</td>
                <td style="text-align: left;">' . htmlspecialchars($row->kategori_pendapatan ?? '-') . '</td>
                <td style="text-align: left;"><b>' . htmlspecialchars($row->layanan_hpp ?? '-') . '</b></td>
                <td class="txt-fmt">' . htmlspecialchars($row->jenis_kendaraan ?? '-') . '</td>
                <td class="num-fmt" style="color: #2563eb; font-weight: bold;">' . (float)$row->hpp . '</td>
                <td class="num-fmt" style="color: #047857;">' . (float)$row->margin_member . '</td>
                <td class="pct-fmt">' . ((float)$row->persen_hpp_member / 100) . '</td>
                <td class="num-fmt" style="color: #1d4ed8;">' . (float)$row->margin_non_member . '</td>
                <td class="pct-fmt">' . ((float)$row->persen_hpp_non_member / 100) . '</td>
            </tr>';
            }

            $html .= '
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="7" style="text-align: right;">TOTAL KESELURUHAN:</td>
                <td class="num-fmt" style="color: #2563eb;">' . (float)$totalHpp . '</td>
                <td class="num-fmt" style="color: #047857;">' . (float)$totalMarginMember . '</td>
                <td style="text-align: center;">-</td>
                <td class="num-fmt" style="color: #1d4ed8;">' . (float)$totalMarginNonMember . '</td>
                <td style="text-align: center;">-</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>';

            fwrite($output, $html);
            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
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