<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property string slug
 * @property string title
 * @property string thumbnail
 * @property int price
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'title', 'thumbnail'];

    protected static function boot()
    {
        parent::boot();
        // TODO 3rd lesson

        static::creating(function (Product $product) {
            $product->slug = $product->slug ?? str($product->title)->slug();
        });
    }

    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
