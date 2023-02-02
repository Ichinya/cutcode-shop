<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property string slug
 * @property string title
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'title'];

    protected static function boot()
    {
        parent::boot();
        // TODO 3rd lesson

        static::creating(function (Category $category) {
            $category->slug = $category->slug ?? str($category->title)->slug();
        });
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
