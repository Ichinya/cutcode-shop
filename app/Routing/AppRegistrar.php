<?php

namespace App\Routing;

use App\Contracts\RouteRegistrar;
use App\Http\Controllers\ThumbnailController;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\Facades\Route;


class AppRegistrar implements RouteRegistrar
{

    public function map(Registrar $registrar): void
    {
        Route::middleware('web')->group(function () {
            Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');

            Route::get('/storage/images/{dir}/{method}/{size}/{file}', ThumbnailController::class)
                ->where('method', 'resize|crop|fit')
                ->where('size', '\d+x\d+')
                ->where('file', '.+\.(png|jpg)$')
                ->name('thumbnail');
        });
    }
}
