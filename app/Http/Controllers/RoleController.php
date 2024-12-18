<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class RoleController extends Controller
{
    //
    // Show all roles
    public function index()
    {
        $roles = Role::all();  // Fetch all roles
        return view('admin.roles.index', compact('roles'));
    }

    // Show form to edit role permissions
    public function edit($id)
    {
        $role = Role::findOrFail($id);

        // Decode permissions from JSON before passing to the view
        // $role->permissions = $role->permissions;

        // Get all permissions from the Permission table
        $permissions = Permission::all()->groupBy('module'); // Group by module

        return view('admin.roles.edit', compact('role', 'permissions'));
    }


    // Update role permissions
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);  // Find the role by ID
        // dd($request->all());

        // Validate the input (you can add more validation rules if needed)
        $validatedData = $request->validate([
            'permissions' => 'required|array', // Ensure permissions are passed as an array
        ]);
        // dd($validatedData);

        // Convert permissions to boolean values (true/false)
        $permissions = $request->permissions;

        // Loop through each permission and convert values to booleans
        foreach ($permissions as $module => $actions) {
            foreach (['read', 'create', 'update', 'delete'] as $action) {
                // Ensure all actions are included and are boolean values
                $permissions[$module][$action] = isset($actions[$action]) ? filter_var($actions[$action], FILTER_VALIDATE_BOOLEAN) : false;
            }
        }
        // dd($permissions);

        // Update the permissions in JSON format
        $role->permissions = $permissions;  // Encode permissions as JSON

        // Save the updated role
        $role->save();

        return redirect()->route('roles.index')->with('success', 'Role permissions updated successfully!');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Delete the role
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully!');
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy('module');
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        // Validate the input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required|array',
        ]);

        // Create a new role
        $role = new Role();
        $role->name = $request->name;

        // Encode the permissions as JSON
        $role->permissions = $request->permissions;

        // Save the new role
        $role->save();

        return redirect()->route('roles.index')->with('success', 'New role created successfully!');
    }
    public function permission_create($id)
    {
        $roles = Role::find($id);
        // dd($roles);
        return view('admin.roles.permission_create', compact('roles'));
    }

    public function permission_store(Request $request)
    {
        // dd($request->all());
        // Validate input
        $validatedData = $request->validate([
            'role_id' => 'required|exists:roles,id', // Ensure role is selected
            'module' => 'required|string|max:255',
            'permissions' => 'required|array', // Validate permissions array
        ]);
        // dd('finee');
        // Find the role by ID
        $role = Role::findOrFail($request->role_id);
        // Check if the module exists in the 'permissions' table
        $existingPermission = Permission::where('module', $request->module)->first();

        if (!$existingPermission) {
            // If the module does not exist, create a new entry in the permissions table
            $newPermission = new Permission();
            $newPermission->module = $request->module;
            $newPermission->save();
        }
        // dd($role);
        // Decode the current permissions
        $permissions = $role->permissions;
        // dd($permissions);

        // Add the new permissions for the given module
        $module = $request->module;
        $permissions[$module] = $request->permissions; // Add the new module and permissions

        // Save the updated permissions back into the role
        $role->permissions = $permissions;
        $role->save();

        return redirect()->route('roles.index')->with('success', 'New permission added successfully!');
    }

    public function permission_delete($module)
    {
        // dd($module);
        $permission = Permission::where('module', $module)->first();
        if (!$permission) {
            return redirect()->route('roles.index')->with('error', 'Permission not found');
        }
        $permission->delete();
        return redirect()->route('roles.index')->with('success', 'Permission Delete Succesfullt');

    }

    public function add_new_permission(Request $request)
    {
        return view('admin.roles.add_new_permission');
    }

    public function permissions_add(Request $request)
    {
        // dd($request->all());
        $permissions = Permission::where('module', $request->module)->first();
        if (!$permissions) {
            $permissions = new Permission();
            $permissions->module = $request->module;
            $permissions->save();

            return redirect()->route('permission_list')->with('success', 'New permission added successfully!');
        } else {
            return redirect()->route('permission_list')->with('error', 'Module already exist');
        }
    }

    public function permission_list()
    {
        $permissionsss = Permission::all();
        return view('admin.roles.permission_list', compact('permissionsss'));
    }





}
