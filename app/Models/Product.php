<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'category_products_id',
        'code',
        'name',
        'description',
        'images',
        'price',
    ];

    protected $casts = [
        'images' => 'array',
        'price'  => 'decimal:2',
    ];

    protected $dates = ['deleted_at'];

    public function categoryProduct()
    {
        return $this->belongsTo(CategoryProduct::class, 'category_products_id');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'products_id');
    }
}
