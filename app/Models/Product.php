<?php

namespace App\Models;

use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Searchable;
use Support\Casts\PriceCast;
use Support\Traits\Models\HasSlug;
use Support\Traits\Models\HasThumbnail;

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
    use HasSlug;
    use HasThumbnail;
    use Searchable;

    protected $fillable = ['slug', 'title', 'thumbnail', 'on_home_page', 'sorting', 'text'];

    protected $casts = [
        'price' => PriceCast::class,
    ];

    public function scopeFiltered(Builder $query)
    {
        return app(Pipeline::class)
            ->send($query)
            ->through(filters())
            ->thenReturn();
    }

    public function scopeSorted(Builder $query)
    {
        $query->when(request('sort'), function (Builder $q) {
            $column = request()->str('sort');
            if ($column->contains(['price', 'title'])) {
                $direction = $column->contains('-') ? 'desc' : 'asc';
                $q->orderBy((string)$column->remove('-'), $direction);
            }
        });
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
//    #[SearchUsingPrefix(['id'])]
    #[SearchUsingFullText(['title', 'text'])]
    public function toSearchableArray(): array
    {
        return [
//            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text,
        ];
    }


    protected function thumbnailDir(): string
    {
        return 'products';
    }

    public function scopeHomePage(Builder $query)
    {
        $query->where('on_home_page', true)
            ->orderBy('sorting')
            ->limit(6);
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
