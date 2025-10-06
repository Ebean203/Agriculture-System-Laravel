<?php

namespace App\Http\Controllers;

use App\Models\MaoStaff;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'MAO Staff Directory - Lagonglong FARMS';

        $staff = MaoStaff::query()
            ->leftJoin('roles as r', 'r.role_id', '=', 'mao_staff.role_id')
            ->select('mao_staff.*', 'r.role as role_name')
            ->orderBy('last_name')
            ->get();

        $roles = Role::query()->orderBy('role')->get(['role_id', 'role']);

        return view('staff.index', compact('pageTitle', 'staff', 'roles'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'contact_number' => 'required|string|max:50',
            'role_id' => 'required|integer|exists:roles,role_id',
            'username' => 'required|string|max:255|unique:mao_staff,username',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::transaction(function () use ($data) {
            MaoStaff::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'position' => $data['position'],
                'contact_number' => $data['contact_number'],
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
                'role_id' => $data['role_id'],
            ]);
        });

        return redirect()->route('staff.index')->with('success', 'Staff member added successfully.');
    }
}
