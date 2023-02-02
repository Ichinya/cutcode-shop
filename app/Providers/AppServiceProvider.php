<?php

namespace App\Providers;

use App\Http\Kernel;
use Carbon\CarbonInterval;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
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
        // если сохраняется поле, которого нет в fillable
        // если в выборке модели нет нужного proporty. Например $user = User::select(['id'])->first(), то при вызове $user->name выдаст exception вместо null
        // теперь в одном методе
        Model::shouldBeStrict(!app()->isProduction());

        if (app()->isProduction()) {
            // долгие запросы от начала до конца работы - общее время работы
            DB::whenQueryingForLongerThan(CarbonInterval::seconds(5), function (Connection $connection) {
                logger()->channel('telegram')->debug('whenQueryingForLongerThan: ' . join('/n', $connection->getQueryLog()));
            });

            // долгие запросы
            DB::listen(function (QueryExecuted $query) {
                if ($query->time > 500) {
                    logger()
                        ->channel('telegram')
                        ->debug('Long QueryExecuted: ' . $query->sql, $query->bindings);
                }
            });

            /** @var Kernel $kernel */
            $kernel = app(Kernel::class);
            $kernel->whenRequestLifecycleIsLongerThan(
                CarbonInterval::seconds(4),
                function () {
                    logger()->channel('telegram')->debug('whenRequestLifecycleIsLongerThan: ' . request()->url());
                }
            );
        }
    }
}
