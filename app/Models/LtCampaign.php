<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LtCampaign extends Model
{
    use SoftDeletes;

    protected $table = 'lt_campaigns';

    protected $fillable = ['brand', 'objective', 'name', 'created_by'];

    public function adsets()
    {
        return $this->hasMany(LtAdset::class, 'campaign_id');
    }
}
