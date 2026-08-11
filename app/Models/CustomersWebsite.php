<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomersWebsite extends Model
{
    protected $table = 'customers_website';
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    public function layouts()
    {
        return $this->hasMany(CustomersWebsiteLayout::class, 'customers_website_id');
    }
}
