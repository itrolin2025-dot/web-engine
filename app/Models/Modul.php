<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modul extends Model
{
    use SoftDeletes;

    protected $fillable = ['parent_id','sort','kode','name','icon','shortcut'];
    protected $dates = ['deleted_at'];

    public function akses()
    {
        return $this->hasMany(ModulAkses::class, 'modul_id');
    }
    
    public function permissions()
    {
        return $this->hasManyThrough(
            RolePermission::class,
            ModulAkses::class,
            'modul_id',          // modul_akses.modul_id
            'modul_akses_id',    // role_permission.modul_akses_id
            'id',                // moduls.id
            'id'                 // modul_akses.id
        );
    }

    public function modulAkses()
    {
        return $this->hasMany(ModulAkses::class, 'modul_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Modul::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Modul::class, 'parent_id')->orderBy('sort_order');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

}
