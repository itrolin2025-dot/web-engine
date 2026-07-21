<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LtTargeting extends Model
{
    use SoftDeletes;

    protected $table = 'lt_targetings';

    protected $fillable = [
        'label',
        'area',
        'interest',
        'created_by',
    ];
}
