<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use Database\Factories\BrandFactory;
use Database\Factories\CategoryFactory;
use Database\Factories\UserFactory;
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
        BrandFactory::new()->count(10)->create();

        CategoryFactory::new()->count(10)->create()
            ->has(Product::factory(rand(5, 15)))
            ->create();

        UserFactory::new()->count(10)->create();

        UserFactory::new()->create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => Hash::make('password')
        ]);
    }
}
