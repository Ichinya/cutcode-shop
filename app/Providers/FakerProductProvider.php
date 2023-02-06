<?php

namespace App\Providers;

use Faker\Provider\Base;

class FakerProductProvider extends Base
{
    public function productImage(string $dir = ''): string
    {
        if (is_dir(storage_path($dir)) && !file_exists(storage_path($dir))) {
            mkdir(storage_path($dir));
        }
        return fake()->file(
            base_path('/tests/Fixtures/images/products'),
            storage_path($dir),
            false
        );
    }
}
