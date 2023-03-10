<?php

namespace App\Providers;

use App\Filters\BrandFilter;
use App\Filters\PriceFilter;
use Domain\Catalog\Filters\FilterManager;
use Domain\Catalog\Sorters\Sorter;
use Illuminate\Support\ServiceProvider;

class CatalogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FilterManager::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        app(FilterManager::class)->registerFilters([
            new PriceFilter(),
            new BrandFilter(),
        ]);

        $this->app->bind(Sorter::class, fn() => new Sorter(['title', 'price']));
    }
}
