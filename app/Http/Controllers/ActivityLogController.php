<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'All Activities - Lagonglong FARMS';

        $search = trim($request->get('search', ''));
        $type = trim($request->get('activity_type', ''));
        $dateFrom = trim($request->get('date_from', ''));
        $dateTo = trim($request->get('date_to', ''));

        $q = DB::table('activity_logs as al')
            ->leftJoin('mao_staff as ms', 'al.staff_id', '=', 'ms.staff_id')
            ->select(
                'al.*',
                DB::raw("CONCAT(ms.first_name, ' ', ms.last_name) as staff_name"),
                'ms.username'
            )
            ->orderBy('al.timestamp', 'desc');

        if ($type !== '') {
            $q->where('al.action_type', $type);
        }
        if ($search !== '') {
            $kw = "%{$search}%";
            $q->where(function($sub) use ($kw){
                $sub->where('al.action', 'like', $kw)
                    ->orWhere('al.details', 'like', $kw)
                    ->orWhere('ms.first_name', 'like', $kw)
                    ->orWhere('ms.last_name', 'like', $kw)
                    ->orWhere('ms.username', 'like', $kw);
            });
        }
        if ($dateFrom !== '') {
            $q->whereDate('al.timestamp', '>=', $dateFrom);
        }
        if ($dateTo !== '') {
            $q->whereDate('al.timestamp', '<=', $dateTo);
        }

        $activities = $q->paginate(10);
        $totalCount = DB::table('activity_logs')->count();

        // Available action types (from enum)
        $types = ['login','farmer','rsbsa','yield','commodity','input','farmer_registration'];

        return view('activities.all', compact(
            'pageTitle','activities','totalCount','search','type','dateFrom','dateTo','types'
        ));
    }
}
