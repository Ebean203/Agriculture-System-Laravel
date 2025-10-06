<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ActivitiesController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('search', ''));
        $activityType = trim($request->get('activity_type', ''));
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = DB::table('mao_activities as a')
            ->leftJoin('mao_staff as s', 'a.staff_id', '=', 's.staff_id')
            ->select('a.*', DB::raw("CONCAT(COALESCE(s.first_name,''),' ',COALESCE(s.last_name,'')) as staff_name"))
            ->orderBy('a.activity_date', 'desc')
            ->orderBy('a.created_at', 'desc');

        if ($search !== '') {
            $q = "%{$search}%";
            $query->where(function($sub) use ($q){
                $sub->where('a.title','like',$q)
                    ->orWhere('a.description','like',$q)
                    ->orWhere('a.location','like',$q)
                    ->orWhere('a.activity_type','like',$q);
            });
        }
        if ($activityType !== '') {
            $query->where('a.activity_type', $activityType);
        }
        if ($dateFrom) { $query->whereDate('a.activity_date','>=',$dateFrom); }
        if ($dateTo) { $query->whereDate('a.activity_date','<=',$dateTo); }

        $activities = $query->paginate(10);

        $staff = DB::table('mao_staff')->select('staff_id', DB::raw("CONCAT(first_name,' ',last_name) as name"))->orderBy('first_name')->get();
        $types = [
            'Training','Meeting','Inspection','Seminar','Workshop','Conference','Field Visit','Other'
        ];

        $pageTitle = 'MAO Activities Management - Lagonglong FARMS';
        return view('activities.index', compact('activities','staff','types','pageTitle','search','activityType','dateFrom','dateTo'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'staff_id' => 'nullable|exists:mao_staff,staff_id',
            'activity_type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'activity_date' => 'required|date',
            'location' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::table('mao_activities')->insert([
            'staff_id' => $request->staff_id ?: null,
            'activity_type' => $request->activity_type,
            'title' => $request->title,
            'description' => $request->description,
            'activity_date' => $request->activity_date,
            'location' => $request->location,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('activities')->with('success', 'Activity added successfully');
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'staff_id' => 'nullable|exists:mao_staff,staff_id',
            'activity_type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'activity_date' => 'required|date',
            'location' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::table('mao_activities')->where('activity_id', $id)->update([
            'staff_id' => $request->staff_id ?: null,
            'activity_type' => $request->activity_type,
            'title' => $request->title,
            'description' => $request->description,
            'activity_date' => $request->activity_date,
            'location' => $request->location,
            'updated_at' => now(),
        ]);

        return redirect()->route('activities')->with('success', 'Activity updated successfully');
    }
}
