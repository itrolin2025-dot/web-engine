<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplatesSection extends Model
{
    protected $table = 'templates_section';
    protected $guarded = [];

    public function contents()
    {
        return $this->hasMany(TemplatesSectionContent::class, 'templates_sections_id');
    }

    public function template()
    {
        return $this->belongsTo(\App\Models\Template::class, 'template_id');
    }

    /**
     * Tags attached to this section (filter by type 'section' in the UI).
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'section_tag', 'templates_section_id', 'tag_id');
    }
}
