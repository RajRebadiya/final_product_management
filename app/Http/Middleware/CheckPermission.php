<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class CheckPermission
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('staff')->user();
        $role = Role::find($user->role_id);
        $permissions = $role->permissions;

        // Define route-to-permission mappings
        $routePermissionMap = [
            'dashboard' => 'Dashboard',
            'category' => 'Category',
            'product' => 'Product',
            'barcode' => 'Barcode',
            'print.product' => 'Barcode',
            'new_offer_form' => 'Offer_Form',
            'offer_form_list' => 'Offer_Form',
            'staff.create' => 'User',
            'staff.index' => 'User',
            'staff.edit' => 'User',
            'staff.update' => 'User',
            'roles.create' => 'Roles',
            'roles.index' => 'Roles',
            'roles.edit' => 'Roles',
            'roles.update' => 'Roles',
            'add_new_permission' => 'Permissions',
            'permission_list' => 'Permissions',
        ];

        // Get current route name
        $routeName = \Route::currentRouteName();

        // Check if the route maps to a permission key
        $permissionKey = $routePermissionMap[$routeName] ?? $routeName;

        // Check if user has read access for this permission key
        if (!empty($permissions[$permissionKey]['read']) && $permissions[$permissionKey]['read']) {
            return $next($request);
        }

        // Render custom "403 Access Forbidden" page
        return response()->view('admin.errors.access_denied', [], 403);
    }
}
