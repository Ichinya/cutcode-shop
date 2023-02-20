<?php

namespace Domain\Catalog\Models;

use App\Models\Product;
use Domain\Catalog\Collections\CategoryCollection;
use Domain\Catalog\QueryBuilders\CategoryQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Support\Traits\Models\HasSlug;


/**
 * @mixin Builder
 * @property string slug
 * @property string title
 * @method static CategoryQueryBuilder|Category query()
 */
class Category extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = ['slug', 'title', 'on_home_page', 'sorting'];

    public function newCollection(array $models = []): CategoryCollection
    {
        return new CategoryCollection($models);
    }


    public function newEloquentBuilder($query): Builder|Category
    {
        return new CategoryQueryBuilder($query);
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
