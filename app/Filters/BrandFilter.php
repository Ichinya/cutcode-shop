<?php

namespace App\Filters;

use Domain\Catalog\Models\Brand;
use Illuminate\Contracts\Database\Eloquent\Builder;

class BrandFilter extends \Domain\Catalog\Filters\AbstractFilter
{

    public function title(): string
    {
        return 'Бренд';
    }

    public function key(): string
    {
        return 'brands';
    }

    public function apply(Builder $query): Builder
    {
        return $query
            ->when($this->requestValue(), function (Builder $q) {
                $q->whereIn('brand_id', $this->requestValue());
            });
    }

    public function value(): array
    {
        return Brand::query()
            ->select(['id', 'title'])
            ->has('products')
            ->get()
            ->pluck('title', 'id')
            ->toArray();
    }

    public function view(): string
    {
        return 'catalog.filters.brands';
    }
}
