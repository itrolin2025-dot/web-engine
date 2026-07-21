<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['name'];

    public function permissions()
    {
        // return $this->belongsToMany(Permissions::class, 'role');
    }

    // Untuk middleware: $user->hasPermission('product.create')
    public function hasPermission($slug)
    {
        // return $this->permissions()->where('slug', $slug)->exists();
    }
    
    public function RolePermission()
    {
        return $this->hasMany(RolePermission::class, 'role_id');
    }
}
