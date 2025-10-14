<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\GeneratedReport;
use App\Models\Farmer;
use App\Models\MaoInventory;
use App\Models\YieldMonitoring;
use App\Models\MaoDistributionLog;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Count saved reports for the current user
        $reportsCount = GeneratedReport::where('staff_id', Auth::id())->count();

        return view('reports.index', [
            'pageTitle' => 'Reports System - Lagonglong FARMS',
            'reportsCount' => $reportsCount,
        ]);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $reportType = $request->report_type;
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Generate report data based on type
        $reportData = $this->generateReportData($reportType, $startDate, $endDate);
        
        // Generate HTML report
        $html = view('reports.templates.' . $reportType, [
            'data' => $reportData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedBy' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
            'generatedAt' => now()
        ])->render();

        // Save report to file system
        $filename = 'report_' . $reportType . '_' . now()->format('Y-m-d_H-i-s') . '.html';
        $filepath = 'reports/' . $filename;
        
        // Create reports directory in public folder if it doesn't exist
        if (!file_exists(public_path('reports'))) {
            mkdir(public_path('reports'), 0755, true);
        }
        
        // Save to public directory (not using Storage facade to match legacy system)
        file_put_contents(public_path($filepath), $html);

        // Save to database using existing generated_reports table structure
        GeneratedReport::create([
            'report_type' => $reportType,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'file_path' => $filepath,
            'staff_id' => Auth::id()
        ]);

        // Log the activity
        DB::table('activity_logs')->insert([
            'staff_id' => Auth::id(),
            'action' => "Generated and saved {$this->getReportTypeLabel($reportType)} report",
            'action_type' => 'farmer',
            'details' => "Report Period: {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}, File: {$filename}",
            'timestamp' => now()
        ]);

        // Return HTML for preview
        return response($html)->header('Content-Type', 'text/html');
    }

    private function generateReportData($reportType, $startDate, $endDate)
    {
        switch ($reportType) {
            case 'farmers_summary':
                return $this->getFarmersSummaryData($startDate, $endDate);
            
            case 'input_distribution':
                return $this->getInputDistributionData($startDate, $endDate);
            
            case 'yield_monitoring':
                return $this->getYieldMonitoringData($startDate, $endDate);
            
            case 'inventory_status':
                return $this->getInventoryStatusData($startDate, $endDate);
            
            case 'barangay_analytics':
                return $this->getBarangayAnalyticsData($startDate, $endDate);
            
            case 'commodity_production':
                return $this->getCommodityProductionData($startDate, $endDate);
            
            case 'registration_analytics':
                return $this->getRegistrationAnalyticsData($startDate, $endDate);
            
            case 'comprehensive_overview':
                return $this->getComprehensiveOverviewData($startDate, $endDate);
            
            default:
                return [];
        }
    }

    private function getFarmersSummaryData($startDate, $endDate)
    {
        // Query farmers with their barangays and commodities
        $farmers = DB::table('farmers as f')
            ->join('barangays as b', 'f.barangay_id', '=', 'b.barangay_id')
            ->leftJoin('farmer_commodities as fc', 'f.farmer_id', '=', 'fc.farmer_id')
            ->leftJoin('commodities as c', 'fc.commodity_id', '=', 'c.commodity_id')
            ->select(
                'f.farmer_id',
                DB::raw('MAX(f.first_name) as first_name'),
                DB::raw('MAX(f.middle_name) as middle_name'),
                DB::raw('MAX(f.last_name) as last_name'),
                DB::raw('MAX(f.suffix) as suffix'),
                DB::raw('MAX(f.birth_date) as birth_date'),
                DB::raw('MAX(f.gender) as gender'),
                DB::raw('MAX(f.contact_number) as contact_number'),
                DB::raw('MAX(f.barangay_id) as barangay_id'),
                DB::raw('MAX(f.address_details) as address_details'),
                DB::raw('MAX(f.is_member_of_4ps) as is_member_of_4ps'),
                DB::raw('MAX(f.is_ip) as is_ip'),
                DB::raw('MAX(f.other_income_source) as other_income_source'),
                DB::raw('MAX(f.land_area_hectares) as land_area_hectares'),
                DB::raw('MAX(f.registration_date) as registration_date'),
                DB::raw('MAX(f.archived) as archived'),
                DB::raw('MAX(f.archive_reason) as archive_reason'),
                DB::raw('MAX(f.is_rsbsa) as is_rsbsa'),
                DB::raw('MAX(f.is_ncfrs) as is_ncfrs'),
                DB::raw('MAX(f.is_boat) as is_boat'),
                DB::raw('MAX(f.is_fisherfolk) as is_fisherfolk'),
                DB::raw('MAX(b.barangay_name) as barangay_name'),
                DB::raw('GROUP_CONCAT(DISTINCT c.commodity_name) as commodities')
            )
            ->whereBetween('f.registration_date', [$startDate, $endDate])
            ->groupBy('f.farmer_id')
            ->get();

        $totalFarmers = $farmers->count();
        $byBarangay = $farmers->groupBy('barangay_name')->map->count();
        
        // Get primary commodities
        $byCommodity = DB::table('farmers as f')
            ->join('farmer_commodities as fc', 'f.farmer_id', '=', 'fc.farmer_id')
            ->join('commodities as c', 'fc.commodity_id', '=', 'c.commodity_id')
            ->whereBetween('f.registration_date', [$startDate, $endDate])
            ->where('fc.is_primary', 1)
            ->select('c.commodity_name', DB::raw('COUNT(*) as count'))
            ->groupBy('c.commodity_name')
            ->pluck('count', 'commodity_name');

        return [
            'farmers' => $farmers,
            'total' => $totalFarmers,
            'byBarangay' => $byBarangay,
            'byCommodity' => $byCommodity
        ];
    }

    private function getInputDistributionData($startDate, $endDate)
    {
        $distributions = DB::table('mao_distribution_log as mdl')
            ->join('farmers as f', 'mdl.farmer_id', '=', 'f.farmer_id')
            ->join('input_categories as ic', 'mdl.input_id', '=', 'ic.input_id')
            ->join('barangays as b', 'f.barangay_id', '=', 'b.barangay_id')
            ->select('mdl.*', 'f.first_name', 'f.last_name', 
                     'b.barangay_name', 'ic.input_name', 'ic.unit')
            ->whereBetween('mdl.date_given', [$startDate, $endDate])
            ->get();

        $totalDistributions = $distributions->count();
        $totalQuantity = $distributions->sum('quantity_distributed');
        $byInput = $distributions->groupBy('input_name')->map(function($items) {
            return [
                'count' => $items->count(),
                'total_quantity' => $items->sum('quantity_distributed')
            ];
        });

        return [
            'distributions' => $distributions,
            'total' => $totalDistributions,
            'totalQuantity' => $totalQuantity,
            'byInput' => $byInput
        ];
    }

    private function getYieldMonitoringData($startDate, $endDate)
    {
        $yields = DB::table('yield_monitoring as ym')
            ->join('farmers as f', 'ym.farmer_id', '=', 'f.farmer_id')
            ->join('commodities as c', 'ym.commodity_id', '=', 'c.commodity_id')
            ->join('barangays as b', 'f.barangay_id', '=', 'b.barangay_id')
            ->select('ym.*', 'f.first_name', 'f.last_name', 
                     'b.barangay_name', 'c.commodity_name')
            ->whereBetween('ym.record_date', [$startDate, $endDate])
            ->get();

        $totalYield = $yields->sum('yield_amount');
        $avgYield = $yields->avg('yield_amount');
        $byCommodity = $yields->groupBy('commodity_name')->map(function($items) {
            return [
                'count' => $items->count(),
                'total_yield' => $items->sum('yield_amount'),
                'avg_yield' => $items->avg('yield_amount')
            ];
        });

        return [
            'yields' => $yields,
            'total' => $totalYield,
            'average' => $avgYield,
            'byCommodity' => $byCommodity
        ];
    }

    private function getInventoryStatusData($startDate, $endDate)
    {
        $inventory = DB::table('mao_inventory as mi')
            ->join('input_categories as ic', 'mi.input_id', '=', 'ic.input_id')
            ->leftJoin('mao_distribution_log as mdl', function ($join) use ($startDate, $endDate) {
                $join->on('mi.input_id', '=', 'mdl.input_id')
                     ->whereBetween('mdl.date_given', [$startDate, $endDate]);
            })
            ->select('mi.*', 'ic.input_name', 'ic.unit', DB::raw('COALESCE(SUM(mdl.quantity_distributed),0) as total_distributed'))
            ->groupBy('mi.inventory_id', 'mi.input_id', 'mi.quantity_on_hand', 'mi.last_updated', 'ic.input_name', 'ic.unit')
            ->get();

        $totalItems = $inventory->count();
        $totalQuantity = $inventory->sum('quantity_on_hand');
        
        // Items that might need restocking (less than 20 units as example)
        $lowStock = $inventory->filter(function($item) {
            return $item->quantity_on_hand < 20;
        });

        return [
            'inventory' => $inventory,
            'totalItems' => $totalItems,
            'totalQuantity' => $totalQuantity,
            'lowStock' => $lowStock
        ];
    }

    private function getBarangayAnalyticsData($startDate, $endDate)
    {
        $barangayData = DB::table('farmers as f')
            ->join('barangays as b', 'f.barangay_id', '=', 'b.barangay_id')
            ->select('b.barangay_name', 
                     DB::raw('COUNT(*) as farmer_count'),
                     DB::raw('SUM(f.land_area_hectares) as total_land_area'))
            ->whereBetween('f.registration_date', [$startDate, $endDate])
            ->groupBy('b.barangay_name')
            ->get();

        return [
            'barangays' => $barangayData
        ];
    }

    private function getCommodityProductionData($startDate, $endDate)
    {
        $commodities = DB::table('yield_monitoring as ym')
            ->join('commodities as c', 'ym.commodity_id', '=', 'c.commodity_id')
            ->select('c.commodity_name', 
                     DB::raw('SUM(ym.yield_amount) as total_production'),
                     DB::raw('AVG(ym.yield_amount) as avg_production'),
                     DB::raw('COUNT(*) as harvest_count'))
            ->whereBetween('ym.record_date', [$startDate, $endDate])
            ->groupBy('c.commodity_name')
            ->get();

        return [
            'commodities' => $commodities
        ];
    }

    private function getRegistrationAnalyticsData($startDate, $endDate)
    {
        $registrations = DB::table('farmers')
            ->select(DB::raw('DATE(registration_date) as date'), 
                     DB::raw('COUNT(*) as count'))
            ->whereBetween('registration_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'registrations' => $registrations
        ];
    }

    private function getComprehensiveOverviewData($startDate, $endDate)
    {
        return [
            'farmers' => $this->getFarmersSummaryData($startDate, $endDate),
            'distributions' => $this->getInputDistributionData($startDate, $endDate),
            'yields' => $this->getYieldMonitoringData($startDate, $endDate),
            'inventory' => $this->getInventoryStatusData($startDate, $endDate)
        ];
    }

    private function getReportTypeLabel($reportType)
    {
        $labels = [
            'farmers_summary' => 'Farmers Summary',
            'input_distribution' => 'Input Distribution',
            'yield_monitoring' => 'Yield Monitoring',
            'inventory_status' => 'Inventory Status',
            'barangay_analytics' => 'Barangay Analytics',
            'commodity_production' => 'Commodity Production',
            'registration_analytics' => 'Registration Analytics',
            'comprehensive_overview' => 'Comprehensive Overview'
        ];

        return $labels[$reportType] ?? ucfirst(str_replace('_', ' ', $reportType));
    }

    public function getSavedReports()
    {
        $reports = GeneratedReport::with('staff')
            ->where('staff_id', Auth::id())
            ->orderBy('timestamp', 'desc')
            ->limit(5)
            ->get()
            ->map(function($report) {
                return [
                    'report_id' => $report->report_id,
                    'report_type' => $this->getReportTypeLabel($report->report_type),
                    'start_date' => $report->start_date->format('Y-m-d'),
                    'end_date' => $report->end_date->format('Y-m-d'),
                    'file_path' => asset($report->file_path),
                    'timestamp' => $report->timestamp->format('Y-m-d H:i:s'),
                    'generated_by' => $report->staff->first_name . ' ' . $report->staff->last_name
                ];
            });

        return response()->json([
            'success' => true,
            'reports' => $reports
        ]);
    }

    public function getSavedReportsCount()
    {
        $count = GeneratedReport::where('staff_id', Auth::id())->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Show a paginated list of all saved reports for the current user
     */
    public function allReports(Request $request)
    {
        $query = GeneratedReport::with('staff')
            ->where('staff_id', Auth::id())
            ->orderBy('timestamp', 'desc');

        if ($request->filled('type')) {
            $query->where('report_type', $request->input('type'));
        }
        if ($request->filled('from')) {
            $query->whereDate('start_date', '>=', $request->input('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('end_date', '<=', $request->input('to'));
        }

        $reports = $query->paginate(15)->withQueryString();

        return view('reports.all', [
            'pageTitle' => 'All Reports - Lagonglong FARMS',
            'reports' => $reports,
            'filter' => [
                'type' => $request->input('type'),
                'from' => $request->input('from'),
                'to' => $request->input('to'),
            ],
        ]);
    }
}
