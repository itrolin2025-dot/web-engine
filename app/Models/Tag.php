<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tags';

    protected $fillable = [
        'code',
        'nama',
        'type',
    ];

    /**
     * Available tag types.
     */
    public const TYPES = ['template', 'section', 'other'];

    /**
     * Templates attached to this tag.
     */
    public function templates()
    {
        return $this->belongsToMany(Template::class, 'template_tag', 'tag_id', 'template_id');
    }

    /**
     * Generate an automatic tag code: TAG-0001 (global sequential, gaps safe).
     */
    public static function generateCode(): string
    {
        $prefix = 'TAG-';

        $last = static::withTrashed()
            ->where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->value('code');

        $next = $last ? ((int) substr($last, strlen($prefix))) + 1 : 1;

        do {
            $code = $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
            $next++;
        } while (static::withTrashed()->where('code', $code)->exists());

        return $code;
    }
}
