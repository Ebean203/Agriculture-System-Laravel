<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farmer;
use App\Models\Commodity;
use App\Models\MaoInventory;
use App\Models\YieldMonitoring;
use App\Models\ActivityLog;
use App\Models\Barangay;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get dashboard statistics using Eloquent models
        
        // Count total farmers
        $total_farmers = Farmer::where('archived', 0)->count();
        
        // Count RSBSA registered farmers  
        $rsbsa_registered = Farmer::where('is_rsbsa', 1)->where('archived', 0)->count();
        
        // Count NCFRS registered farmers
        $ncfrs_registered = Farmer::where('is_ncfrs', 1)->where('archived', 0)->count();
        
        // Count Fisherfolk registered farmers
        $fisherfolk_registered = Farmer::where('is_fisherfolk', 1)->where('archived', 0)->count();
        
        // Count farmers with boats
        $total_boats = Farmer::where('is_boat', 1)->where('archived', 0)->count();
        
        // Count total commodities
        $total_commodities = Commodity::count();
        
        // Count total inventory items in stock
        $total_inventory = MaoInventory::where('quantity_on_hand', '>', 0)->sum('quantity_on_hand') ?? 0;
        
        // Count recent yield records (last 30 days)
        $recent_yields = YieldMonitoring::where('record_date', '>=', now()->subDays(30))->count();
        
        // Get recent activities from activity logs
        $recent_activities = ActivityLog::with('staff')
            ->orderBy('timestamp', 'desc')
            ->limit(10)
            ->get();
        
        // Get yield records per barangay for charts
        $yield_records_per_barangay = DB::table('yield_monitoring as ym')
            ->join('farmers as f', 'ym.farmer_id', '=', 'f.farmer_id')
            ->join('barangays as b', 'f.barangay_id', '=', 'b.barangay_id')
            ->select('b.barangay_name', DB::raw('COUNT(ym.yield_id) as record_count'), DB::raw('SUM(ym.yield_amount) as total_yield'))
            ->groupBy('b.barangay_name')
            ->orderBy('b.barangay_name', 'asc')
            ->get();
        
        // Get yield records for the table
        $yield_records = YieldMonitoring::with(['farmer', 'commodity'])
            ->orderBy('record_date', 'desc')
            ->limit(10)
            ->get();
        
        // Get farmers by program for pie chart
        $farmers_by_program = [
            'RSBSA' => $rsbsa_registered,
            'NCFRS' => $ncfrs_registered,
            'Fisherfolk' => $fisherfolk_registered,
            'With Boats' => $total_boats
        ];
        
        // Get barangays for filter dropdown
        $barangays = Barangay::orderBy('barangay_name')->get();
        
        // Prepare data for modals
        $commodities = Commodity::orderBy('commodity_name')->get();
        
        // Return view with all data
        return view('dashboard', compact(
            'total_farmers',
            'rsbsa_registered', 
            'ncfrs_registered',
            'fisherfolk_registered',
            'total_boats',
            'total_commodities',
            'total_inventory',
            'recent_yields',
            'recent_activities',
            'yield_records',
            'yield_records_per_barangay',
            'farmers_by_program',
            'barangays',
            'commodities'
        ));
    }
    
    /**
     * Get chart data for yield monitoring
     */
    public function getYieldData(Request $request)
    {
        $labels = [];
        $data = [];
        
        $yields = DB::table('yield_monitoring as ym')
            ->join('farmers as f', 'ym.farmer_id', '=', 'f.farmer_id')
            ->join('barangays as b', 'f.barangay_id', '=', 'b.barangay_id')
            ->select('b.barangay_name', DB::raw('SUM(ym.yield_amount) as total_yield'))
            ->groupBy('b.barangay_name')
            ->orderBy('b.barangay_name', 'asc')
            ->get();
        
        foreach ($yields as $yield) {
            $labels[] = $yield->barangay_name;
            $data[] = (float)$yield->total_yield;
        }
        
        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
}
