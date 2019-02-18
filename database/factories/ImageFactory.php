<?php

$factory->define(App\Image::class, function (Faker\Generator $faker) {
    return [
        "name" => $faker->name,
        "order" => $faker->randomNumber(2),
        "status" => collect(["1","0",])->random(),
    ];
});
