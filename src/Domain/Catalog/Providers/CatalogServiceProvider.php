<?php

namespace Domain\Catalog\Providers;

use Illuminate\Support\ServiceProvider;

class CatalogServiceProvider extends ServiceProvider
{

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {

        //
    }

    public function register(): void
    {
        $this->app->register(
            ActionsServiceProvider::class
        );

        $this->app->register(
            EventsServiceProvider::class
        );
    }
}
