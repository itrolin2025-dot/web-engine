<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplatesSectionContent extends Model
{
    protected $table = 'templates_sections_content';
    protected $guarded = [];

    public function section()
    {
        return $this->belongsTo(TemplatesSection::class, 'templates_sections_id');
    }
}
