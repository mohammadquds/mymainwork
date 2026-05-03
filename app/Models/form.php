<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class form extends Model
{
    use LogsActivity;


    // this the activity log tracker will track every thing

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }


    protected $fillable = [
    'user_id',
    'invoice_number',
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
    'unit_type',
    'description',
];

protected $casts = [
    'date_of_birth' => 'date',
    'weight' => 'float',
    'sale_price' => 'float',
];
}
