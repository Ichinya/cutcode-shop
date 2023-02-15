<?php

namespace App\Routing;

use App\Contracts\RouteRegistrar;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\Facades\Route;


class AppRegistrar implements RouteRegistrar
{

    public function map(Registrar $registrar): void
    {
        Route::middleware('web')->group(function () {
            Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');
        });
    }
}
