<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

      // The table name is optional if the model is using the plural form of the table name.
      protected $table = 'colors';
}
