<?php


if (!function_exists('flash')) {
    function flash(): \Support\Flash\Flash
    {
        return app(\Support\Flash\Flash::class);
    }

}
if (!function_exists('filters')) {
    function filters(): array
    {
        return app(\Domain\Catalog\Filters\FilterManager::class)->items();
    }

}
