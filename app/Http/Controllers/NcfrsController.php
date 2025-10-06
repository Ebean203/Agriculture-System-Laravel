<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NcfrsController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'NCFRS Records - Lagonglong FARMS';

        $search = trim($request->get('search', ''));
        $barangay = $request->get('barangay');

        // Total count for NCFRS-registered and not archived
        $countQuery = DB::table('farmers as f')
            ->where('f.archived', 0)
            ->where('f.is_ncfrs', 1);

        if ($search !== '') {
            $q = "%{$search}%";
            $countQuery->where(function ($sub) use ($q) {
                $sub->where('f.first_name', 'like', $q)
                    ->orWhere('f.middle_name', 'like', $q)
                    ->orWhere('f.last_name', 'like', $q)
                    ->orWhere('f.contact_number', 'like', $q)
                    ->orWhere('f.farmer_id', 'like', $q)
                    ->orWhere(DB::raw("CONCAT(f.first_name,' ',COALESCE(f.middle_name,''),' ',f.last_name)"), 'like', $q);
            });
        }
        if ($barangay) {
            $countQuery->where('f.barangay_id', $barangay);
        }

        $totalRecords = $countQuery->distinct('f.farmer_id')->count('f.farmer_id');

        // Listing query
        $query = DB::table('farmers as f')
            ->leftJoin('barangays as b', 'f.barangay_id', '=', 'b.barangay_id')
            ->leftJoin('farmer_commodities as fc', 'fc.farmer_id', '=', 'f.farmer_id')
            ->leftJoin('commodities as c', 'c.commodity_id', '=', 'fc.commodity_id')
            ->where('f.archived', 0)
            ->where('f.is_ncfrs', 1)
            ->select(
                'f.farmer_id',
                'f.first_name', 'f.middle_name', 'f.last_name', 'f.suffix',
                'f.birth_date',
                'f.contact_number',
                'b.barangay_name',
                'f.registration_date',
                DB::raw("GROUP_CONCAT(DISTINCT c.commodity_name ORDER BY c.commodity_name SEPARATOR ', ') as commodities_info")
            )
            ->groupBy(
                'f.farmer_id',
                'f.first_name', 'f.middle_name', 'f.last_name', 'f.suffix',
                'f.birth_date',
                'f.contact_number',
                'b.barangay_name',
                'f.registration_date'
            )
            ->orderBy('f.registration_date', 'desc');

        if ($search !== '') {
            $q = "%{$search}%";
            $query->where(function ($sub) use ($q) {
                $sub->where('f.first_name', 'like', $q)
                    ->orWhere('f.middle_name', 'like', $q)
                    ->orWhere('f.last_name', 'like', $q)
                    ->orWhere('f.contact_number', 'like', $q)
                    ->orWhere('f.farmer_id', 'like', $q)
                    ->orWhere(DB::raw("CONCAT(f.first_name,' ',COALESCE(f.middle_name,''),' ',f.last_name)"), 'like', $q);
            });
        }
        if ($barangay) {
            $query->where('f.barangay_id', $barangay);
        }

        $farmers = $query->paginate(10);

        $barangays = DB::table('barangays')->select('barangay_id', 'barangay_name')->orderBy('barangay_name')->get();

        return view('ncfrs.index', [
            'pageTitle' => $pageTitle,
            'farmers' => $farmers,
            'barangays' => $barangays,
            'search' => $search,
            'barangay' => $barangay,
            'totalRecords' => $totalRecords,
        ]);
    }
}
