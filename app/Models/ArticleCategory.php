<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'article_categories';

    protected $fillable = [
        'customers_id',
        'name',
        'description',
    ];

    protected $dates = ['deleted_at'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customers_id');
    }
}
