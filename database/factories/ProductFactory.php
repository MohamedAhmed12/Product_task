<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [

        // Using faker to seed the products table with dummy data
        'name'          => $faker->word,
        'price'         => $faker->numberBetween(100, 2500),
        'image'         => $faker->imageUrl(400, 339),
        'category_id'   => $faker->numberBetween(1, 8),

    ];
});
