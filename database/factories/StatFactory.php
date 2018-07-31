<?php declare(strict_types = 1);

use Faker\Generator as Faker;

$factory->define(
    App\Models\Api\StarCitizen\Stat::class,
    function (Faker $faker) {
        return [
            'funds' => $faker->numberBetween(1000000, 13000000000),
            'fleet' => $faker->numberBetween(1000, 1500000),
            'fans' => $faker->numberBetween(1000, 2000000),
        ];
    }
);