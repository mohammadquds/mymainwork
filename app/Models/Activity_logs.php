<?php

namespace App\Models;
class Activity_logs extends Model
{

protected $fillable = [
    'user_id',
    'name',
    'description',
    'type',
    'section',
    'date',
];  }