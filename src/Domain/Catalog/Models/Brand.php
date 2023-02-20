<?php

namespace Domain\Catalog\Models;

use App\Models\Product;
use Domain\Catalog\QueryBuilders\BrandQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Support\Traits\Models\HasSlug;
use Support\Traits\Models\HasThumbnail;

/**
 * @mixin Builder
 * @property string slug
 * @property string title
 * @property string thumbnail
 * @method static BrandQueryBuilder|Brand query()
 */
class Brand extends Model
{
    use HasFactory;
    use HasSlug;
    use HasThumbnail;

    protected $fillable = ['slug', 'title', 'thumbnail', 'on_home_page', 'sorting'];

    protected function thumbnailDir(): string
    {
        return 'brands';
    }

    public function newEloquentBuilder($query): Builder|Brand
    {
        return new BrandQueryBuilder($query);
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }
}
