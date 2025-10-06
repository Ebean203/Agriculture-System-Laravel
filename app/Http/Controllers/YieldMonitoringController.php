<?php

namespace App\Http\Controllers;

use App\Models\YieldMonitoring;
use App\Models\Farmer;
use App\Models\Commodity;
use App\Models\Barangay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class YieldMonitoringController extends Controller
{
    public function index(Request $request)
    {
        // Filters
        $farmerSearch = trim($request->get('farmer_search', ''));
        $farmerId = trim($request->get('farmer_id', ''));
        $categoryId = trim($request->get('category_filter', ''));
        $commodityId = trim($request->get('commodity_filter', ''));
        $dateFilter = trim($request->get('date_filter', ''));

        // Base query with joins for display
        $query = DB::table('yield_monitoring as ym')
            ->leftJoin('farmers as f', 'ym.farmer_id', '=', 'f.farmer_id')
            ->leftJoin('barangays as b', 'f.barangay_id', '=', 'b.barangay_id')
            ->leftJoin('commodities as c', 'ym.commodity_id', '=', 'c.commodity_id')
            ->leftJoin('commodity_categories as cc', 'c.category_id', '=', 'cc.category_id')
            ->select('ym.*', 'f.first_name', 'f.middle_name', 'f.last_name', 'f.suffix', 'f.contact_number', 'b.barangay_name', 'c.commodity_name', 'cc.category_id', 'cc.category_name')
            ->orderBy('ym.record_date', 'desc');

        if ($farmerId !== '') {
            $query->where('f.farmer_id', $farmerId);
        } elseif ($farmerSearch !== '') {
            $q = "%{$farmerSearch}%";
            $query->where(function($sub) use ($q) {
                $sub->where('f.first_name', 'like', $q)
                    ->orWhere('f.last_name', 'like', $q)
                    ->orWhere(DB::raw("CONCAT(f.first_name, ' ', COALESCE(f.middle_name, ''), ' ', f.last_name)"), 'like', $q)
                    ->orWhere(DB::raw("CONCAT(f.first_name, CASE WHEN f.middle_name IS NOT NULL AND LOWER(f.middle_name) NOT IN ('n/a','na','') THEN CONCAT(' ', f.middle_name) ELSE '' END, ' ', f.last_name, CASE WHEN f.suffix IS NOT NULL AND LOWER(f.suffix) NOT IN ('n/a','na','') THEN CONCAT(' ', f.suffix) ELSE '' END)"), 'like', $q);
            });
        }

        if ($categoryId !== '') {
            $query->where('cc.category_id', $categoryId);
        }
        if ($commodityId !== '') {
            $query->where('c.commodity_id', $commodityId);
        }
        if ($dateFilter !== '') {
            // Accept either specific date (YYYY-MM-DD) or relative periods like 7,30,90 days
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFilter)) {
                $query->whereDate('ym.record_date', $dateFilter);
            } else {
                $days = (int) $dateFilter;
                if ($days > 0) {
                    $query->whereDate('ym.record_date', '>=', now()->subDays($days)->toDateString());
                }
            }
        }

        $yieldRecords = $query->paginate(10);

        // Summary metrics based on current filtered set (clone the query without pagination)
        $base = DB::table('yield_monitoring as ym')
            ->leftJoin('farmers as f', 'ym.farmer_id', '=', 'f.farmer_id')
            ->leftJoin('commodities as c', 'ym.commodity_id', '=', 'c.commodity_id')
            ->leftJoin('commodity_categories as cc', 'c.category_id', '=', 'cc.category_id');
        // Apply same filters
        if ($farmerId !== '') {
            $base->where('f.farmer_id', $farmerId);
        } elseif ($farmerSearch !== '') {
            $q = "%{$farmerSearch}%";
            $base->where(function($sub) use ($q) {
                $sub->where('f.first_name', 'like', $q)
                    ->orWhere('f.last_name', 'like', $q)
                    ->orWhere(DB::raw("CONCAT(f.first_name, ' ', COALESCE(f.middle_name, ''), ' ', f.last_name)"), 'like', $q)
                    ->orWhere(DB::raw("CONCAT(f.first_name, CASE WHEN f.middle_name IS NOT NULL AND LOWER(f.middle_name) NOT IN ('n/a','na','') THEN CONCAT(' ', f.middle_name) ELSE '' END, ' ', f.last_name, CASE WHEN f.suffix IS NOT NULL AND LOWER(f.suffix) NOT IN ('n/a','na','') THEN CONCAT(' ', f.suffix) ELSE '' END)"), 'like', $q);
            });
        }
        if ($categoryId !== '') { $base->where('cc.category_id', $categoryId); }
        if ($commodityId !== '') { $base->where('c.commodity_id', $commodityId); }
        if ($dateFilter !== '') {
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFilter)) {
                $base->whereDate('ym.record_date', $dateFilter);
            } else {
                $days = (int) $dateFilter; if ($days > 0) { $base->whereDate('ym.record_date', '>=', now()->subDays($days)->toDateString()); }
            }
        }

        $summary = [
            'total_visits' => (clone $base)->count(),
            'agronomic' => (clone $base)->where('cc.category_id', 1)->count(),
            'high_value' => (clone $base)->where('cc.category_id', 2)->count(),
            'livestock_poultry' => (clone $base)->whereIn('cc.category_id', [3,4])->count(),
            'average_yield' => (clone $base)->avg('ym.yield_amount') ?? 0,
        ];

        // Data for filters
        $commodities = DB::table('commodities')
            ->select('commodity_id','commodity_name','category_id')
            ->orderBy('commodity_name')
            ->get();
        $pageTitle = 'Yield Monitoring - Lagonglong FARMS';

        return view('yield.monitoring', compact(
            'yieldRecords', 'commodities', 'summary', 'pageTitle',
            'farmerSearch', 'farmerId', 'categoryId', 'commodityId', 'dateFilter'
        ));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmers,farmer_id',
            'commodity_id' => 'required|exists:commodities,commodity_id',
            'season' => 'required|string|max:100',
            'yield_amount' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:20',
            'distributed_input' => 'nullable|string|max:100',
            'visit_date' => 'nullable|date',
            'quality_grade' => 'nullable|string|max:50',
            'growth_stage' => 'nullable|string|max:50',
            'field_conditions' => 'nullable|string|max:100',
            'visit_notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            YieldMonitoring::create([
                'farmer_id' => $request->farmer_id,
                'commodity_id' => $request->commodity_id,
                'season' => $request->season,
                'record_date' => $request->visit_date ? $request->visit_date : now(),
                'yield_amount' => $request->yield_amount,
                'unit' => $request->unit,
                // Optional additional fields if they exist in schema; ignoring if not present
            ]);

            return redirect()->route('yield-monitoring')->with('success', 'Yield record added successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to record yield: ' . $e->getMessage()])->withInput();
        }
    }
}
