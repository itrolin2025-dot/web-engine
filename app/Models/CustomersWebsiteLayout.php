<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomersWebsiteLayout extends Model
{
    protected $table = 'customers_websites_layout';
    protected $guarded = [];

    public function website()
    {
        return $this->belongsTo(CustomersWebsite::class, 'customers_website_id');
    }

    public function section()
    {
        return $this->belongsTo(TemplatesSection::class, 'templates_section_id');
    }

    public function templateContent()
    {
        return $this->belongsTo(TemplatesSectionContent::class, 'template_content_id');
    }
}
