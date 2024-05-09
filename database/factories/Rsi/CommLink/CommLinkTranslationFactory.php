<?php

declare(strict_types=1);

namespace Database\Factories\Rsi\CommLink;

use App\Models\Rsi\CommLink\CommLinkTranslation;
use App\Models\System\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommLinkTranslationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CommLinkTranslation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'locale_code' => Language::ENGLISH,
            'translation' => $this->faker->randomHtml(2, 3),
        ];
    }

    public function german()
    {
        return $this->state(
            function (array $attributes) {
                return [
                    'locale_code' => Language::GERMAN,
                    'translation' => $this->faker->randomHtml(2, 3),
                ];
            }
        );
    }
}
