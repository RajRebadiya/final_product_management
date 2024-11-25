<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;  // Import this trait
class Staff extends Authenticatable{
    use HasFactory , HasApiTokens;
    protected $table = 'tbl_staff';
}
