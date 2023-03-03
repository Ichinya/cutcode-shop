<?php

namespace App\Routing;

use App\Contracts\RouteRegistrar;
use App\Http\Controllers\ProductController;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\Facades\Route;


class ProductRegistrar implements RouteRegistrar
{

    public function map(Registrar $registrar): void
    {
        Route::middleware('web')->group(function () {
            Route::get('products/{product:slug}', ProductController::class)->name('products');
        });
    }
}
