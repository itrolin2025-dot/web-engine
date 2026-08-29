<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'articles';

    protected $fillable = [
        'customers_id',
        'article_categories_id',
        'title',
        'subtitle',
        'description',
        'author',
        'published_date',
        'images',
    ];

    protected $casts = [
        'images'         => 'array',
        'published_date' => 'date',
    ];

    protected $dates = ['deleted_at'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customers_id');
    }

    public function articleCategory()
    {
        return $this->belongsTo(ArticleCategory::class, 'article_categories_id');
    }
}
