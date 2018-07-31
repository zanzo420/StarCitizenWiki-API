<?php declare(strict_types = 1);

use Faker\Generator as Faker;

$factory->define(
    \App\Models\Api\Notification::class,
    function (Faker $faker) {
        return [
            'level' => $faker->numberBetween(0, 3),
            'content' => $faker->realText(200),
            'order' => $faker->numberBetween(0, 4),
            'output_status' => $faker->boolean(),
            'output_email' => $faker->boolean(),
            'output_index' => $faker->boolean(),
            'expired_at' => $faker->dateTime,
            'published_at' => $faker->dateTime,
            'created_at' => $faker->dateTime,
            'updated_at' => $faker->dateTime,
        ];
    }
);

$factory->state(
    \App\Models\Api\Notification::class,
    'active',
    [
        'expired_at' => \Carbon\Carbon::now()->addWeek(),
    ]
);

$factory->state(
    \App\Models\Api\Notification::class,
    'expired',
    [
        'expired_at' => \Carbon\Carbon::now()->subDay(),
    ]
);

$factory->state(
    \App\Models\Api\Notification::class,
    'not_published',
    [
        'published_at' => \Carbon\Carbon::now()->addWeek(),
    ]
);