<?php

declare(strict_types=1);

namespace App\Http\Resources\SC\ItemSpecification;

use App\Http\Resources\AbstractBaseResource;
use App\Http\Resources\SC\ItemSpecification\MiningLaser\MiningLaserModifierResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'mining_module_v2',
    title: 'Mining Module',
    properties: [
        new OA\Property(property: 'type', type: 'string', nullable: true),
        new OA\Property(property: 'uses', type: 'integer', nullable: true),
        new OA\Property(property: 'duration', type: 'string', nullable: true),
        new OA\Property(
            property: 'modifiers',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/mining_laser_modifiers_v2')
        ),
    ],
    type: 'object'
)]
class MiningModuleResource extends AbstractBaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->getDescriptionDatum('Item Type'),
            'uses' => (int) $this->uses,
            'duration' => $this->duration,
            'modifiers' => MiningLaserModifierResource::collection($this->modifiers),
        ];
    }
}
