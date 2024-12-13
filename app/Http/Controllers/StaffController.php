<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Role;
class StaffController extends Controller
{
    //
    public function index()
    {
        $staffs = Staff::with('role')->where('status', 1)->get();
        return view('admin.staff.index', compact('staffs'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.staff.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|min:10|max:10|unique:tbl_staff,mobile_no',
            'email' => 'required|email|unique:tbl_staff,email',
            'password' => 'required|min:6',
            'c_password' => 'required|min:6|same:password',
            'role' => 'required|exists:roles,id',
        ]);

        // Fetch the last staff record
        $lastStaff = Staff::latest('id')->first();

        // Generate the next emp_code
        if ($lastStaff && $lastStaff->emp_code) {
            $nextEmpCode = (int) $lastStaff->emp_code + 1;
        } else {
            // Default starting code if no staff exists
            $nextEmpCode = 1001; // Starting emp_code
        }

        // Create new staff
        $staff = new Staff();
        $staff->emp_code = $nextEmpCode;
        $staff->name = $request->name;
        $staff->mobile_no = $request->mobile_no;
        $staff->email = $request->email ?? null;
        $staff->role_id = $request->role;
        $staff->status = 1;
        $staff->password = $request->password; // Encrypt the password
        $staff->save();

        return redirect()->route('staff.index')->with('success', 'Staff created successfully with Employee Code: ' . $nextEmpCode);
    }

    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        $roles = Role::all();
        return view('admin.staff.edit', compact('staff', 'roles'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|min:10|max:10',
            'email' => 'required|email|exists:tbl_staff,email',
            'role' => 'required|exists:roles,id',
        ]);

        $staff = Staff::findOrFail($id);
        $staff->name = $request->name ?? $staff->name;
        $staff->mobile_no = $request->mobile_no ?? $staff->mobile_no;
        $staff->email = $request->email ?? $staff->email;
        $staff->role_id = $request->role ?? $staff->role_id;
        $staff->status = 1;
        $staff->password = $request->password ?? $staff->password;
        $staff->save();

        return redirect()->route('staff.index')->with('success', 'Staff updated successfully.');
    }

    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->status = 0;
        $staff->save();
        return redirect()->route('staff.index')->with('success', 'Staff deleted successfully.');
    }
}
