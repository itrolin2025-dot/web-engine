<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModulAkses extends Model
{
    use SoftDeletes;
    protected $fillable = ['modul_id', 'akses'];
    protected $dates = ['deleted_at'];

    public function modul()
    {
        return $this->belongsTo(Modul::class, 'modul_id');
    }
    
    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class, 'modul_akses_id');
    }
    
    
}
