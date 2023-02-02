<?php

namespace App\Models;

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

    protected $fillable = ['slug', 'title', 'thumbnail'];

    protected static function boot()
    {
        parent::boot();
        // TODO 3rd lesson

        static::creating(function (Brand $brand) {
            $brand->slug = $brand->slug ?? str($brand->title)->slug();
        });
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }
}
