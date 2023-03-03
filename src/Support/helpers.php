<?php

if (!function_exists('sorter')) {
    function sorter(): \Domain\Catalog\Sorters\Sorter
    {
        return app(\Domain\Catalog\Sorters\Sorter::class);
    }
}

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

if (!function_exists('is_catalog_view')) {
    function is_catalog_view(string $type, string $default = 'grid'): bool
    {
        return session('view', $default) === $type;
    }
}

if (!function_exists('filter_url')) {
    function filter_url(?\Domain\Catalog\Models\Category $category, array $params = []): string
    {
        return route('catalog', $category, [
            ...request()->only(['sort', 'filters']),
            ...$params,
            'category' => $category
        ]);
    }
}
