<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes;
    protected $fillable = ['code', 'name', 'departemen_id', 'position', 'email', 'phone', 'address', 'date_join','photo', 'status', 'is_active'];
    protected $dates = ['deleted_at'];
    protected $table = 'staffs';

    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }
}
