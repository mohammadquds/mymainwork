<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity_logs extends Model
{
    protected $fillable = [
        'user_id',
        'user_role',
        'user_email',
        'activity_type',
        'description',
        'section',
        'date',
    ];
    function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
