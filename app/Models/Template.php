<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    
    protected $table = 'template';
    protected $guarded = [];

    /**
     * Tags attached to this template.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'template_tag', 'template_id', 'tag_id');
    }
}
