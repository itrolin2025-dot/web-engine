<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LtAdset extends Model
{
    use SoftDeletes;

    protected $table = 'lt_adsets';

    protected $fillable = ['campaign_id', 'targeting_id', 'name', 'conversion', 'created_by'];

    public function campaign()
    {
        return $this->belongsTo(LtCampaign::class, 'campaign_id');
    }

    public function targeting()
    {
        return $this->belongsTo(LtTargeting::class, 'targeting_id');
    }

    public function creatives()
    {
        return $this->hasMany(LtCreative::class, 'adset_id');
    }
}
