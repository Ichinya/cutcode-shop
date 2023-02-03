<?php

namespace App\Models;

use App\Traits\Models\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 * @property string slug
 * @property string title
 * @property string thumbnail
 */
class Brand extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = ['slug', 'title', 'thumbnail'];

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }
}
