<?php

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Cache\BrandCacheEnum;
use Domain\Catalog\Models\Brand;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Support\Traits\Makeable;

class BrandViewModel
{
    use Makeable;

    public function homePage(): Collection|array
    {
        // Если будут изменения данных по категориям, то в обсервере скидываем кэш
        return Cache::rememberForever(BrandCacheEnum::BrandHomePage->key(), function () {
            return Brand::query()
                ->homePage()
                ->get();
        });
    }
}
