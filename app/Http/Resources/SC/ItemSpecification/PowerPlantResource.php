<?php

declare(strict_types=1);

namespace App\Http\Resources\SC\ItemSpecification;

use App\Http\Resources\AbstractBaseResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'power_plant_v2',
    title: 'Power Plant',
    properties: [
        new OA\Property(property: 'power_output', type: 'double', nullable: true),
    ],
    type: 'object'
)]
class PowerPlantResource extends AbstractBaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'power_output' => $this->power_output,
        ];
    }
}
