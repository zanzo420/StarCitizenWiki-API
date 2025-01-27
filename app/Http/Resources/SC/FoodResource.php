<?php

declare(strict_types=1);

namespace App\Http\Resources\SC;

use App\Http\Resources\AbstractBaseResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'food_item_v2',
    title: 'Food Item',
    allOf: [
        new OA\Schema(ref: '#/components/schemas/item_base_v2'),
        new OA\Schema(properties: [new OA\Property(property: 'food', ref: '#/components/schemas/food_v2')], type: 'object'),
    ],
)]

#[OA\Schema(
    schema: 'food_v2',
    title: 'Food',
    properties: [
        new OA\Property(property: 'nutritional_density_rating', type: 'integer', nullable: true),
        new OA\Property(property: 'hydration_efficacy_index', type: 'integer', nullable: true),
        new OA\Property(property: 'container_type', type: 'string', nullable: true),
        new OA\Property(property: 'one_shot_consume', type: 'boolean', nullable: true),
        new OA\Property(property: 'can_be_reclosed', type: 'boolean', nullable: true),
        new OA\Property(property: 'discard_when_consumed', type: 'boolean', nullable: true),
        new OA\Property(
            property: 'effects',
            type: 'array',
            items: new OA\Items(type: 'string'),
            nullable: true,
        ),
    ],
    type: 'object',
)]
class FoodResource extends AbstractBaseResource
{
    public static function validIncludes(): array
    {
        return [
            'effects',
            'shops',
            'shops.items',
        ];
    }

    public function toArray(Request $request): array
    {
        return [
            'nutritional_density_rating' => $this->nutritional_density_rating,
            'hydration_efficacy_index' => $this->hydration_efficacy_index,
            'container_type' => $this->container_type,
            'one_shot_consume' => $this->one_shot_consume,
            'can_be_reclosed' => $this->can_be_reclosed,
            'discard_when_consumed' => $this->discard_when_consumed,
            'effects' => $this->effects->map(function ($effect) {
                return $effect->name;
            }),
        ];
    }
}
