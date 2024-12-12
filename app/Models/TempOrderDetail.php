<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempOrderDetail extends Model
{
    use HasFactory;

    // Table name (optional if using default naming convention)
    protected $table = 'temp_order_details';


    /**
     * Relationship: A TempOrderDetail belongs to a TempOrder.
     */
    public function tempOrder()
    {
        return $this->belongsTo(TempOrder::class, 'temp_order_id', 'id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'id', 'id'); // Adjust column names as per your database schema
    }
}
