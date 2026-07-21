<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LtCreative extends Model
{
    use SoftDeletes;

    protected $table = 'lt_creatives';

    protected $fillable = ['adset_id', 'ref', 'name', 'format', 'no', 'spend', 'created_by'];

    public function adset()
    {
        return $this->belongsTo(LtAdset::class, 'adset_id');
    }
}
