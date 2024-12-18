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
        // Get current authenticated user
        $user = Auth::guard('staff')->user();

        // Retrieve the role of the user
        $role = Role::find($user->role_id);

        // If the role doesn't exist, deny access
        if (!$role) {
            return response()->view('admin.errors.access_denied', [], 403);
        }

        // Retrieve permissions for the user's role
        $permissions = $role->permissions;

        // Dynamically generate route-to-permission map
        $routePermissionMap = collect(\Route::getRoutes())->mapWithKeys(function ($route) {
            $routeName = $route->getName();

            // Map route names to their corresponding permissions (this logic can be customized)
            if (strpos($routeName, 'product') !== false) {
                return [$routeName => 'Product'];
            }

            if (strpos($routeName, 'category') !== false) {
                return [$routeName => 'Category'];
            }

            if (strpos($routeName, 'offer_form') !== false) {
                return [$routeName => 'Offer_Form'];
            }

            if (strpos($routeName, 'staff') !== false) {
                return [$routeName => 'User'];
            }

            if (strpos($routeName, 'roles') !== false) {
                return [$routeName => 'Roles'];
            }

            if (strpos($routeName, 'permission') !== false) {
                return [$routeName => 'Permissions'];
            }

            if (strpos($routeName, 'barcode') !== false) {
                return [$routeName => 'Barcode'];
            }

            // Default to using route name as the permission key if no other match found
            return [$routeName => ucfirst($routeName)];
        })->toArray();

        // Get the current route name
        $routeName = \Route::currentRouteName();

        // Check if the route has a corresponding permission key
        $permissionKey = $routePermissionMap[$routeName] ?? null;

        // If no permission key is found, deny access
        if (!$permissionKey || !isset($permissions[$permissionKey]['read']) || !$permissions[$permissionKey]['read']) {
            return response()->view('admin.errors.access_denied', [], 403);
        }

        // Proceed to the next middleware if permission check passes
        return $next($request);
    }
}