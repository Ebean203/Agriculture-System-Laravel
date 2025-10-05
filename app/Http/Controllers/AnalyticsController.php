<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\Barangay;
use App\Models\Commodity;
use App\Models\YieldMonitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard
     */
    public function index(Request $request)
    {
        // Get date range from request or set defaults
        $start_date = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $end_date = $request->input('end_date', now()->format('Y-m-d'));
        $report_type = $request->input('report_type', '');
        $barangay_filter = $request->input('barangay_filter', '');

        // Get barangays for filter dropdown
        $barangays = Barangay::orderBy('barangay_name')->get();

        // Get summary statistics
        $summary = $this->getSummaryStats($start_date, $end_date, $barangay_filter);

        // Get chart data if report type is selected
        $chartData = null;
        if ($report_type) {
            $chartData = $this->getChartData($report_type, $start_date, $end_date, $barangay_filter);
        }

        return view('analytics.index', compact(
            'barangays',
            'summary',
            'chartData',
            'start_date',
            'end_date',
            'report_type',
            'barangay_filter'
        ));
    }

    /**
     * Get summary statistics
     */
    private function getSummaryStats($start_date, $end_date, $barangay_filter = '')
    {
        $query = Farmer::whereBetween(DB::raw('DATE(registration_date)'), [$start_date, $end_date]);
        
        if ($barangay_filter) {
            $query->where('barangay_id', $barangay_filter);
        }

        $total_farmers = $query->count();

        // Total yield
        $yieldQuery = YieldMonitoring::whereBetween(DB::raw('DATE(record_date)'), [$start_date, $end_date]);
        if ($barangay_filter) {
            $yieldQuery->whereHas('farmer', function($q) use ($barangay_filter) {
                $q->where('barangay_id', $barangay_filter);
            });
        }
        $total_yield = $yieldQuery->sum('yield_amount');

        // Total boats
        $boatsQuery = Farmer::where('is_boat', 1)
            ->whereBetween(DB::raw('DATE(registration_date)'), [$start_date, $end_date]);
        if ($barangay_filter) {
            $boatsQuery->where('barangay_id', $barangay_filter);
        }
        $total_boats = $boatsQuery->count();

        // Total commodities
        $commoditiesQuery = DB::table('commodities')
            ->join('farmer_commodities', 'commodities.commodity_id', '=', 'farmer_commodities.commodity_id')
            ->join('farmers', 'farmer_commodities.farmer_id', '=', 'farmers.farmer_id')
            ->whereBetween(DB::raw('DATE(farmers.registration_date)'), [$start_date, $end_date]);
        
        if ($barangay_filter) {
            $commoditiesQuery->where('farmers.barangay_id', $barangay_filter);
        }
        
        $total_commodities = $commoditiesQuery->distinct('commodities.commodity_id')->count();

        return [
            'total_farmers' => $total_farmers,
            'total_yield' => $total_yield,
            'total_boats' => $total_boats,
            'total_commodities' => $total_commodities
        ];
    }

    /**
     * Get chart data based on report type
     */
    private function getChartData($report_type, $start_date, $end_date, $barangay_filter = '')
    {
        switch ($report_type) {
            case 'farmer_registrations':
                return $this->getFarmerRegistrations($start_date, $end_date, $barangay_filter);
            case 'yield_monitoring':
                return $this->getYieldData($start_date, $end_date, $barangay_filter);
            case 'commodity_distribution':
                return $this->getCommodityDistribution($start_date, $end_date, $barangay_filter);
            case 'barangay_analytics':
                return $this->getBarangayDistribution($start_date, $end_date, $barangay_filter);
            case 'registration_status':
                return $this->getRegistrationStatus($start_date, $end_date, $barangay_filter);
            default:
                return null;
        }
    }

    /**
     * Get farmer registrations over time
     */
    private function getFarmerRegistrations($start_date, $end_date, $barangay_filter = '')
    {
        $query = DB::table('farmers')
            ->select(DB::raw('DATE(registration_date) as date, COUNT(*) as count'))
            ->whereBetween(DB::raw('DATE(registration_date)'), [$start_date, $end_date]);

        if ($barangay_filter) {
            $query->where('barangay_id', $barangay_filter);
        }

        $data = $query->groupBy(DB::raw('DATE(registration_date)'))
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date')->toArray(),
            'data' => $data->pluck('count')->toArray(),
            'reportType' => 'farmer_registrations',
            'label' => 'Farmers Registered'
        ];
    }

    /**
     * Get yield monitoring data
     */
    private function getYieldData($start_date, $end_date, $barangay_filter = '')
    {
        $query = DB::table('yield_monitoring')
            ->join('farmers', 'yield_monitoring.farmer_id', '=', 'farmers.farmer_id')
            ->select(DB::raw('DATE(yield_monitoring.record_date) as date, SUM(yield_monitoring.yield_amount) as total_yield'))
            ->whereBetween(DB::raw('DATE(yield_monitoring.record_date)'), [$start_date, $end_date]);

        if ($barangay_filter) {
            $query->where('farmers.barangay_id', $barangay_filter);
        }

        $data = $query->groupBy(DB::raw('DATE(yield_monitoring.record_date)'))
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date')->toArray(),
            'data' => $data->pluck('total_yield')->toArray(),
            'reportType' => 'yield_monitoring',
            'label' => 'Total Yield (kg)'
        ];
    }

    /**
     * Get commodity distribution
     */
    private function getCommodityDistribution($start_date, $end_date, $barangay_filter = '')
    {
        $query = DB::table('commodities')
            ->join('farmer_commodities', 'commodities.commodity_id', '=', 'farmer_commodities.commodity_id')
            ->join('farmers', 'farmer_commodities.farmer_id', '=', 'farmers.farmer_id')
            ->select('commodities.commodity_name', DB::raw('COUNT(DISTINCT farmers.farmer_id) as farmer_count'))
            ->whereBetween(DB::raw('DATE(farmers.registration_date)'), [$start_date, $end_date]);

        if ($barangay_filter) {
            $query->where('farmers.barangay_id', $barangay_filter);
        }

        $data = $query->groupBy('commodities.commodity_name')
            ->orderByDesc('farmer_count')
            ->get();

        return [
            'labels' => $data->pluck('commodity_name')->toArray(),
            'data' => $data->pluck('farmer_count')->toArray(),
            'reportType' => 'commodity_distribution',
            'label' => 'Farmers per Commodity'
        ];
    }

    /**
     * Get barangay distribution
     */
    private function getBarangayDistribution($start_date, $end_date, $barangay_filter = '')
    {
        $query = DB::table('farmers')
            ->join('barangays', 'farmers.barangay_id', '=', 'barangays.barangay_id')
            ->select('barangays.barangay_name', DB::raw('COUNT(farmers.farmer_id) as farmer_count'))
            ->whereBetween(DB::raw('DATE(farmers.registration_date)'), [$start_date, $end_date]);

        if ($barangay_filter) {
            $query->where('farmers.barangay_id', $barangay_filter);
        }

        $data = $query->groupBy('barangays.barangay_name')
            ->orderByDesc('farmer_count')
            ->limit(10)
            ->get();

        return [
            'labels' => $data->pluck('barangay_name')->toArray(),
            'data' => $data->pluck('farmer_count')->toArray(),
            'reportType' => 'barangay_analytics',
            'label' => 'Farmers per Barangay'
        ];
    }

    /**
     * Get registration status comparison
     */
    private function getRegistrationStatus($start_date, $end_date, $barangay_filter = '')
    {
        $query = Farmer::whereBetween(DB::raw('DATE(registration_date)'), [$start_date, $end_date]);
        
        if ($barangay_filter) {
            $query->where('barangay_id', $barangay_filter);
        }

        $total_farmers = $query->count();

        // Get RSBSA registered
        $rsbsa_count = (clone $query)->where('is_rsbsa', 1)->count();
        
        // Get NCFRS registered
        $ncfrs_count = (clone $query)->where('is_ncfrs', 1)->count();
        
        // Get Fisherfolk registered
        $fisherfolk_count = (clone $query)->where('is_fisherfolk', 1)->count();
        
        // Get registered in at least one category
        $registered_count = (clone $query)
            ->where(function($q) {
                $q->where('is_rsbsa', 1)
                  ->orWhere('is_ncfrs', 1)
                  ->orWhere('is_fisherfolk', 1);
            })->count();

        $not_registered_count = max(0, $total_farmers - $registered_count);

        return [
            'labels' => ['RSBSA Registered', 'NCFRS Registered', 'Fisherfolk Registered', 'Not Registered'],
            'data' => [$rsbsa_count, $ncfrs_count, $fisherfolk_count, $not_registered_count],
            'reportType' => 'registration_status',
            'label' => 'Registration Status'
        ];
    }
}
