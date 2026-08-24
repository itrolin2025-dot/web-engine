<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $table = 'product_reviews';

    protected $fillable = [
        'products_id',
        'rating',
        'name',
        'profile_photo',
        'comment',
        'status',
        'photos',
    ];

    protected $casts = [
        'rating' => 'integer',
        'status' => 'boolean',
        'photos' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }
}
