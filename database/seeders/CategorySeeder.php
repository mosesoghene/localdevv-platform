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
        $categories = [
            [
                'name' => 'Scripts',
                'slug' => 'scripts',
                'description' => 'PHP and JavaScript scripts for various purposes',
            ],
            [
                'name' => 'Themes',
                'slug' => 'themes',
                'description' => 'Website and application themes',
            ],
            [
                'name' => 'Plugins',
                'slug' => 'plugins',
                'description' => 'WordPress, Laravel and other plugins',
            ],
            [
                'name' => 'Templates',
                'slug' => 'templates',
                'description' => 'HTML, CSS and JavaScript templates',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
