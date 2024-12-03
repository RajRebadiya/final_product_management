<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempOrder extends Model
{
    use HasFactory;

    // Table name (optional if using default naming convention)
    protected $table = 'temp_orders';

    /**
     * Relationship: A TempOrder has many TempOrderDetails.
     */
    public function details()
    {
        return $this->hasMany(TempOrderDetail::class, 'temp_order_id', 'id');
    }
    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function tempOrderDetails()
    {
        return $this->hasMany(TempOrderDetail::class, 'temp_order_id');
    }

    /**
     * Boot method to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically generate `order_number` when creating a new order
        static::creating(function ($model) {
            $lastOrder = self::orderBy('order_number', 'desc')->pluck('order_number')->first();
            $nextOrderNumber = $lastOrder ? (int) substr($lastOrder, 2) + 1 : 1001;
            $model->order_number = 'A_' . $nextOrderNumber;
        });
    }
}
