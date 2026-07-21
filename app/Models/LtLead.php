<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LtLead extends Model
{
    use SoftDeletes;

    protected $table = 'lt_leads';

    protected $fillable = ['lead_date', 'ref', 'title', 'name', 'wa', 'status', 'created_by'];
}
