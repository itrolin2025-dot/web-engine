<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function customersWebsite()
    {
        return $this->belongsTo(CustomersWebsite::class, 'customers_website_id');
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * Generate an automatic transaction code: TRX-YYYYMMDD-NNNN (sequential per day).
     * Shared between the admin module and the storefront checkout.
     */
    public static function generateCode(): string
    {
        $prefix = 'TRX-' . date('Ymd') . '-';

        $lastToday = static::withTrashed()
            ->where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->value('code');

        $next = $lastToday ? ((int) substr($lastToday, strlen($prefix))) + 1 : 1;

        do {
            $code = $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
            $next++;
        } while (static::withTrashed()->where('code', $code)->exists());

        return $code;
    }
}
