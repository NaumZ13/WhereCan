<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect(['Electronics', 'Books', 'Clothes', 'Shoes', 'Accessories'])->map(function ($category) {
            return Category::create(['name' => $category]);
        });
    }
}
