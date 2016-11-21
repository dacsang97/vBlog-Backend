<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Post::class, function (Faker\Generator $faker) {
    $title = $faker->sentence(rand(3, 10));
    return [
        'title' => substr($title, 0, strlen($title) - 1),
        'content' => $faker->text,
        'user_id' => 1,
        'category_id' => $faker->numberBetween(1, 4),
    ];
});
