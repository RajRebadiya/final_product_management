<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function register()
    {

        return view('admin.auth.register');
    }

    public function login()
    {

        return view('admin.auth.login');
    }

    public function register_staff(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:255|unique:tbl_staff,mobile_no|min:10|max:10',
            'password' => 'required|string|min:8|same:confirm_password',
            'confirm_password' => 'required'
        ]);

        // Fetch the last emp_code from tbl_staff, or default to 1000 if no records exist
        $lastEmpCode = Staff::max('emp_code') ?? 1000;

        $staff = new Staff();
        $staff->name = $request->name;
        $staff->mobile_no = $request->mobile_no;
        $staff->password = $request->password;
        $staff->emp_code = $lastEmpCode + 1;
        $staff->permission = '1,2,3';
        $staff->status = 0;
        $staff->save();
        return redirect()->route('login')->with('success', 'Staff added successfully');
    }

    public function login_staff(Request $request)
    {
        $request->validate([
            'mobile_no' => 'required|string|max:255',
            'password' => 'required|string|min:8'
        ]);

        // Fetch the staff member by mobile number
        $staff = Staff::where('mobile_no', $request->mobile_no)->first();

        if (!$staff || $request->password !== $staff->password) {
            return redirect()->back()->with('error', 'Invalid credentials');
        }

        // Manually log the staff in
        Auth::guard('staff')->login($staff);

        return redirect()->route('dashboard_2')->with('success', 'Login successful');
    }

    public function logout()
    {
        if (auth()->guard('staff')->check()) {
            auth()->guard('staff')->logout();
            return redirect()->route('login')->with('success', 'Staff Logout successful');
        } else {
            return redirect()->route('login')->with('success', 'Logout successful');
        }
    }
}
