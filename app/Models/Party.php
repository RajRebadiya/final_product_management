<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;

    protected $table = 'party'; // Specify the table name

    protected $fillable = [
        'name',
        'email',
        'mobile_no',
        'address',
        'city',
        'gst_no',
        'booking',
        'pincode',
        'haste',
        'export',
    ];

    /**
     * Set the GST No in uppercase.
     *
     * @param  string  $value
     * @return void
     */
    public function setGstNoAttribute($value)
    {
        $this->attributes['gst_no'] = strtoupper($value);
    }
}
