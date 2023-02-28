<?php

namespace App\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;

class PriceFilter extends \Domain\Catalog\Filters\AbstractFilter
{

    public function title(): string
    {
        return 'Цена';
    }

    public function key(): string
    {
        return 'price';
    }

    public function apply(Builder $query): Builder
    {
        return $query
            ->when($this->requestValue(), function (Builder $q) {
                $q->whereBetween('price', [
                    $this->requestValue('from', 0) * 100,
                    $this->requestValue('to', 100_000) * 100
                ]);
            });
    }

    public function value(): array
    {
        return [
            'from' => 0,
            'to' => 100_000,
        ];
    }

    public function view(): string
    {
        return 'catalog.filters.price';
    }
}
