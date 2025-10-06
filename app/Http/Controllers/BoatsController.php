<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoatsController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Boat Records - Lagonglong FARMS';

        $search = trim($request->get('search', ''));
        $barangay = $request->get('barangay');

        // Legacy logic: list farmers who are registered as boat owners (farmers.is_boat = 1)
        $query = DB::table('farmers as f')
            ->join('barangays as b', 'f.barangay_id', '=', 'b.barangay_id')
            ->leftJoin('farmer_commodities as fc', function($join){
                $join->on('fc.farmer_id','=','f.farmer_id')->where('fc.is_primary',1);
            })
            ->leftJoin('commodities as c', 'fc.commodity_id', '=', 'c.commodity_id')
            ->where('f.is_boat', 1)
            ->select(
                'f.farmer_id',
                DB::raw("CONCAT(f.first_name,' ',COALESCE(f.middle_name,''),' ',f.last_name,' ',COALESCE(f.suffix,'')) as farmer_name"),
                'f.contact_number',
                'b.barangay_name',
                'c.commodity_name',
                'f.registration_date'
            )
            ->orderBy('f.registration_date','desc');

        if ($search !== '') {
            $q = "%{$search}%";
            $query->where(function($sub) use ($q){
                $sub->where('f.first_name','like',$q)
                    ->orWhere('f.middle_name','like',$q)
                    ->orWhere('f.last_name','like',$q)
                    ->orWhere('f.contact_number','like',$q)
                    ->orWhere('f.farmer_id','like',$q);
            });
        }
        if ($barangay) {
            $query->where('b.barangay_id', $barangay);
        }

        $boats = $query->paginate(10);
        $totalRegistered = (clone $query)->count();

        $barangays = DB::table('barangays')->select('barangay_id','barangay_name')->orderBy('barangay_name')->get();

        return view('boats.index', compact('pageTitle','boats','barangays','search','barangay','totalRegistered'));
    }
}
