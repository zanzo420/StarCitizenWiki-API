<?php

declare(strict_types=1);

namespace App\Http\Resources\SC\MeleeCombatConfig;

use App\Http\Resources\AbstractBaseResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'melee_damage_v2',
    title: 'Melee Damages',
    description: 'Melee Damages',
    properties: [
        new OA\Property(property: 'name', type: 'string', nullable: true),
        new OA\Property(property: 'damage', type: 'double', nullable: true),
    ],
    type: 'object'
)]
class MeleeDamageResource extends AbstractBaseResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'damage' => $this->damage,
        ];
    }
}
