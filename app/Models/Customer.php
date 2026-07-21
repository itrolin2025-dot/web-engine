<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id']; // Allow mass assignment for all except ID

    // or use fillable:
    /*
    protected $fillable = [
        'code',
        'customer_lead_id',
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
        'is_active',
        'created_by',
    ];
    */
}
