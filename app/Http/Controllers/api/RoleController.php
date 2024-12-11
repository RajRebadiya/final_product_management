<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    //

    // Get the details of all roles
    public function getRolesDetails()
    {
        $roles = Role::all(); // Get all roles from the roles table

        // Return the details of each role
        return response()->json([
            'roles' => $roles
        ]);
    }

    // Get the details of a specific role and its associated permissions
    public function getRolePermissionsDetails(Request $request)
    {
        // Find the role by its ID
        $roleId = $request->input('id');
        $role = Role::find($roleId);

        if (!$role) {
            return response()->json([
                'message' => 'Role not found'
            ], 404);
        }

        // Return role details and its permissions
        return response()->json([
            'role' => $role,
        ]);
    }
}
