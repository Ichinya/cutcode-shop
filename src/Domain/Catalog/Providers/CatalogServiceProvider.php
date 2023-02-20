<?php

namespace Domain\Catalog\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
        $this->app->register(ActionsServiceProvider::class);
    }
}
