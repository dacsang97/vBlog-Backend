<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoriesTableSeeder extends Seeder {

    public function run()
    {
        $categories = [
            ['name' => 'Web Developer'],
            ['name' => 'Android Developer'],
            ['name' => 'iOS Developer'],
            ['name' => 'UI/UX'],
            ['name' => 'Tips/Tricks']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}