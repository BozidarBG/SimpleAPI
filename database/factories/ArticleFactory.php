<?php

use Faker\Generator as Faker;

$factory->define(App\Article::class, function (Faker $faker) {
    return [
        'user_id'=>random_int(1,5),
        'title'=>$faker->sentence(5),
        'body'=>$faker->text(300),
        'image'=>null,


    ];
});
