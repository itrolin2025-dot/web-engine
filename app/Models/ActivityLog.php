<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'module',
        'action',
        'transaction_id',
        'user_id',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}

