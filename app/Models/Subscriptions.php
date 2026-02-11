<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
   protected $fillable = [
        'user_id',
        'company_name',
        'type',
        'email',
        'phone_number',
        'Commercial_Registration_Number',
        'vat_number',
        'duration',
        'status',
        'start_date',
        'end_date',
    ];
    function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
