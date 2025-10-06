<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistributionsController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Input Distribution Records - Lagonglong FARMS';

        $search = trim($request->get('search', ''));
        $barangay = $request->get('barangay');
        $inputId = $request->get('input_id');

        $query = DB::table('mao_distribution_log as mdl')
            ->join('farmers as f', 'mdl.farmer_id', '=', 'f.farmer_id')
            ->join('barangays as b', 'f.barangay_id', '=', 'b.barangay_id')
            ->join('input_categories as ic', 'mdl.input_id', '=', 'ic.input_id')
            ->select(
                'mdl.*',
                DB::raw("CONCAT(f.first_name,' ',COALESCE(f.middle_name,''),' ',f.last_name,' ',COALESCE(f.suffix,'')) as farmer_name"),
                'f.contact_number',
                'b.barangay_name',
                'ic.input_name',
                'ic.unit'
            )
            ->orderBy('mdl.date_given', 'desc')
            // Table mao_distribution_log has no created_at; use primary key for tie-breaker
            ->orderBy('mdl.log_id', 'desc');

        if ($search !== '') {
            $q = "%{$search}%";
            $query->where(function($sub) use ($q) {
                $sub->where('f.first_name','like',$q)
                    ->orWhere('f.middle_name','like',$q)
                    ->orWhere('f.last_name','like',$q)
                    ->orWhere('f.suffix','like',$q)
                    ->orWhere('f.contact_number','like',$q);
            });
        }
        if ($barangay) {
            $query->where('b.barangay_id', $barangay);
        }
        if ($inputId) {
            $query->where('ic.input_id', $inputId);
        }

        $records = $query->paginate(10);

        $barangays = DB::table('barangays')->select('barangay_id','barangay_name')->orderBy('barangay_name')->get();
        $inputs = DB::table('input_categories')->select('input_id','input_name','unit')->orderBy('input_name')->get();

        $totalCount = DB::table('mao_distribution_log')->count();

        return view('distributions.index', compact('pageTitle','records','barangays','inputs','search','barangay','inputId','totalCount'));
    }
}
