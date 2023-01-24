<?php

namespace App\Providers;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // ошибки если есть проблемы с моделями
        // если плодятся запросы
        Model::preventLazyLoading(!app()->isProduction());
        // если сохраняется поле, которого нет в fillable
        Model::preventSilentlyDiscardingAttributes(!app()->isProduction());

        // долгие запросы
        DB::whenQueryingForLongerThan(500, function (Connection $connection) {
            // fix logging
        });

        // TODO request cycle
    }
}
