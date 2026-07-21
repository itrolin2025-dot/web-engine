<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerLead extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer_leads';

    protected $fillable = [
        'code',
        'name',
        'source',
        'interest',
        'email',
        'phone',
        'province',
        'city',
        'address',
        'photo',
        'status',
        'created_by',
    ];

    
}
