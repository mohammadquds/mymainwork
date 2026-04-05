<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class form extends Model
{
    protected $fillable = [
    'full_name',
    'national_id',
    'id_version_number',
    'date_of_birth',
    'store_name',
    'employee_name',
    'weight',
    'karat',
    'sale_price',
    'product_image',
];

// Always cast dates so Carbon can handle them
protected $casts = [
    'date_of_birth' => 'date',
    'weight' => 'float',
    'sale_price' => 'float',
];
}
