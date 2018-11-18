<?php

use Faker\Generator as Faker;

$factory->define(App\Comment::class, function (Faker $faker) {
    return [
        'user_id'=>random_int(1,5),
        'article_id'=>random_int(1,100),
        'body'=>$faker->text(100),
    ];
});
