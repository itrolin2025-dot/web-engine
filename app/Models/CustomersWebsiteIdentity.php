<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomersWebsiteIdentity extends Model
{
    protected $table = 'customers_website_identities';
    protected $guarded = ['id'];

    public function customersWebsite()
    {
        return $this->belongsTo(CustomersWebsite::class, 'customers_website_id');
    }
}
