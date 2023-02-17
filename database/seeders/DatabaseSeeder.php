<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        Brand::factory(20)->create();

        Category::factory(10)
            ->has(Product::factory(rand(5, 15)))
            ->create();

        UserFactory::new()->create();

        UserFactory::new()->create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => Hash::make('password')
        ]);
    }
}
