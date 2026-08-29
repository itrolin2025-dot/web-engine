<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplatesSectionContent extends Model
{
    use SoftDeletes;

    protected $table = 'templates_sections_content';
    protected $guarded = [];

    public function section()
    {
        return $this->belongsTo(TemplatesSection::class, 'templates_sections_id');
    }
}
