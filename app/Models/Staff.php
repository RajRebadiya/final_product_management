<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;  // Import this trait

class Staff extends Authenticatable
{
    use HasFactory, HasApiTokens;
    protected $table = 'tbl_staff';
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Check if user has permission for a given module action
    public function hasPermission($module, $action)
    {
        $permissions = $this->role->permissions[$module] ?? null;

        return $permissions && isset($permissions[$action]) && $permissions[$action] === true;
    }
}
