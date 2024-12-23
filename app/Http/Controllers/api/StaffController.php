<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    //
    public function api_login(Request $request)
    {

        $rules = [
            'email' => 'required|string|max:255|email',
            'password' => 'required|string|min:6',
        ];

        // Validate the incoming request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(' ', $errors);
            return response()->json([
                'status_code' => 400,
                'message' => $errorMessage,
                'data' => []
            ]);
        }

        // Fetch staff member by mobile number
        $staff = Staff::where('email', $request->email)->first();

        // Validate password
        if (!$staff || $request->password !== $staff->password) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Generate Sanctum token
        $token = $staff->createToken('staff-token')->plainTextToken;

        return response()->json([
            'status_code' => 200,
            'message' => 'Login successful',
            'token' => $token,
            'data' => $staff
        ]);
    }

    public function api_logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
    
    public function api_login_2(Request $request)
{
    $rules = [
        'email' => 'required|string|max:255|email',
        'password' => 'required|string|min:6',
    ];

    // Validate the incoming request
    $validator = Validator::make($request->all(), $rules);

    // Check if validation fails
    if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $errorMessage = implode(' ', $errors);
        return response()->json([
            'status_code' => 400,
            'message' => $errorMessage,
            'data' => []
        ]);
    }

    // Fetch staff member by email
    $staff = Staff::where('email', $request->email)->first();

    // Validate password
    if (!$staff || $request->password !== $staff->password) {
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    // Check if the staff is already logged in (status == 1)
    if ($staff->login_status == 1) {
        return response()->json([
            'status_code' => 403,
            'message' => 'User already logged in from another device. Please Contact Admin.',
             'data' => (object)[]
        ]);
    }

    // Generate Sanctum token
    $token = $staff->createToken('staff-token')->plainTextToken;

    // Update the status to 1 (logged in)
    $staff->login_status = 1;
    $staff->save();

    return response()->json([
        'status_code' => 200,
        'message' => 'Login successful',
        'token' => $token,
        'data' => $staff
    ]);
}

public function api_logout_2(Request $request)
{
    // Delete the current access token
    $request->user()->currentAccessToken()->delete();

    // Update the status to 0 (logged out)
    $staff = Staff::find($request->user()->id);
    if ($staff) {
        $staff->login_status = 0;
        $staff->save();
    }

    return response()->json(['message' => 'Logged out successfully']);
}

public function reset_login(Request $request)
{
    $rules = [
        'user_id' => 'required|integer|exists:tbl_staff,id',
    ];

    // Validate the incoming request
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $errorMessage = implode(' ', $errors);
        return response()->json([
            'status_code' => 400,
            'message' => $errorMessage,
            'data' => []
        ]);
    }

    // Find the user by ID
    $staff = Staff::find($request->user_id);

    if ($staff) {
        // Revoke all tokens for the user
        $staff->tokens()->delete();

        // Update the status to 0 (logged out)
        $staff->login_status = 0;
        $staff->save();

        return response()->json([
            'status_code' => 200,
            'message' => 'User login reset successfully',
            'data' => $staff
        ]);
    }

    return response()->json([
        'status_code' => 404,
        'message' => 'User not found',
        'data' => []
    ]);
}

    public function get_profile(Request $request)
    {
        // dd('finee');
        if (auth()->check()) {
            // dd('fine');
            $user = Auth::user();

            if ($user) {
                return response()->json([
                    'status_code' => 200,
                    'message' => 'Profile successfully fetched',
                    'data' => $user
                ]);
            }

            return response()->json([
                'status_code' => 401,
                'message' => 'Unauthorized. User not found.'
            ]);
        }
    }

    public function get_permissions(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user (staff)

        if (!$user) {
            return response()->json([
                'status_code' => 404,
                'message' => 'User not found',
            ], 404);
        }

        // Get the comma-separated permission IDs from the user
        $permissionIds = $user->permission; // Assuming this field stores values like '1,2,3'
        // dd($permissionIds);

        if (empty($permissionIds)) {
            return response()->json([
                'status_code' => 404,
                'message' => 'No permissions found for this user',
            ], 404);
        }

        // Convert the comma-separated string into an array of integers
        $permissionIdsArray = explode(',', $permissionIds);
        // dd($permissionIdsArray);

        // Get the permission names based on the IDs
        $permissions = DB::table('permissions')
            ->whereIn('id', $permissionIdsArray)
            ->select('id', 'name') // Select both 'id' and 'name'
            ->get();
        return response()->json([
            'status_code' => 200,
            'message' => 'Permissions successfully fetched',
            'data' => $permissions
        ]);
    }
    public function get_config(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user (staff)

        if (!$user) {
            return response()->json([
                'status_code' => 404,
                'message' => 'User not found',
            ], 404);
        }

        $colors = Color::all();

        if (empty($colors)) {
            return response()->json([
                'status_code' => 404,
                'message' => 'No colors found',
            ], 404);
        }

        return response()->json([
            'status_code' => 200,
            'message' => 'Colors successfully fetched',
            'color_list' => $colors
        ]);
    }


    public function get_colors(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user (staff)

        if (!$user) {
            return response()->json([
                'status_code' => 404,
                'message' => 'User not found',
            ], 404);
        }

        $searchQuery = $request->input('search'); // Get the search query from the request

        $colors = Color::where(function ($query) use ($searchQuery) {
            if ($searchQuery) {
                $query->where('color_name', 'like', '%' . $searchQuery . '%');
            }
        })->get();

        if (empty($colors)) {
            return response()->json([
                'status_code' => 404,
                'message' => 'No colors found',
            ]);
        }

        return response()->json([
            'status_code' => 200,
            'message' => 'Colors successfully fetched',
            'color_list' => $colors
        ]);
    }

    public function add_color(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user (staff)

        if (!$user) {
            return response()->json([
                'status_code' => 404,
                'message' => 'User not found',
            ], 404);
        }

        $rules = [
            'color_name' => 'required',
        ];

        // Validate the incoming request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(' ', $errors);
            return response()->json([
                'status_code' => 400,
                'message' => $errorMessage,
                'data' => []
            ]);
        }

        // Check for duplicate color name in the database
        $existingColor = Color::where('color_name', $request->color_name)->first();

        if ($existingColor) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Color name already exists',
                'data' => []
            ]);
        }

        // Add the new color
        $color = new Color();
        $color->color_name = $request->color_name;
        $color->save();

        return response()->json([
            'status_code' => 200,
            'message' => 'Color added successfully',
        ]);
    }

    public function forget_password(Request $request)
    {
        $rules = [
            'id' => 'required',
            'password' => 'required|min:6',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(' ', $errors);
            return response()->json([
                'status_code' => 400,
                'message' => $errorMessage,
                'data' => []
            ]);
        }
        $staff = Staff::where('id', $request->id)->first();
        if (!$staff) {
            return response()->json([
                'status_code' => 404,
                'message' => 'User not found',
            ]);
        }

        if ($staff) {
            $staff->password = $request->password;
            $staff->save();
            return response()->json([
                'status_code' => 200,
                'message' => 'Password changed successfully',
            ]);
        }
    }
    public function staff_list(Request $request)
    {
        // Fetch staff with their roles
        $staff = Staff::with('role')->get();

        // Transform the data to include the role name directly
        $staffData = $staff->map(function ($staff) {
            return [
                'id' => $staff->id,
                'name' => $staff->name,
                'email' => $staff->email,
                'market_name' => $staff->market_name,
                'mobile_no' => $staff->mobile_no,
                'password' => $staff->password,
                'emp_code' => $staff->emp_code,
                'status' => $staff->status,
                'login_status' => $staff->login_status,
                'updated_at' => $staff->updated_at,
                'role_name' => $staff->role->name ?? 'No Role Assigned', // Include role name
            ];
        });

        return response()->json([
            'status_code' => 200,
            'message' => 'Staff list fetched successfully',
            'data' => $staffData,
        ]);
    }
}
