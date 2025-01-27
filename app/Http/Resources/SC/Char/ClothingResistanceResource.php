<?php

declare(strict_types=1);

namespace App\Http\Resources\SC\Char;

use App\Http\Resources\AbstractBaseResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'clothing_resistance_v2',
    title: 'Clothing Resistance',
    description: 'Resistance of Clothes or Armors',
    properties: [
        new OA\Property(property: 'type', description: 'Resistance type, e.g. min temperature', type: 'string'),
        new OA\Property(
            property: 'threshold',
            description: 'Threshold for this resistance. For temperatures this is the minimum and maximum temperature.',
            type: 'double',
            nullable: true
        ),
        new OA\Property(
            property: 'multiplier',
            description: 'Multiplier, if resistance is not thresholded.',
            type: 'double',
            nullable: true
        ),
    ],
    type: 'object'
)]
class ClothingResistanceResource extends AbstractBaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->type,
            'threshold' => $this->threshold,
            'multiplier' => $this->multiplier,
        ];
    }
}
