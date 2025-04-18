<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a category with specific attributes
        Category::create([
            'user_id' => 1,
            'type' => 'expense',
            'name' => 'testing',
        ]);
    }
}
