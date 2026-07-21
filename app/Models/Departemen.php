<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departemen extends Model
{
    use SoftDeletes;
    protected $fillable = ['name'];
    protected $dates = ['deleted_at'];

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }
}
