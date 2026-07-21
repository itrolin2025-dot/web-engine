<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RolePermission extends Model
{
    use SoftDeletes;

    protected $table = 'role_permission';
    public $timestamps = false;
    protected $dates = ['deleted_at'];
    protected $fillable = ['role_id', 'permission_id', 'modul_akses_id'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function modulAkses()
    {
        return $this->belongsTo(ModulAkses::class, 'modul_akses_id');
    }
}
