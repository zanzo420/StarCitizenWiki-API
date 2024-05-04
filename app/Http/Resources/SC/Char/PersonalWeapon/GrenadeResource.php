<?php

declare(strict_types=1);

namespace App\Http\Resources\SC\Char\PersonalWeapon;

use App\Http\Resources\AbstractBaseResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'grenade_v2',
    title: 'Grenade',
    description: 'FPS Grenade',
    properties: [
        new OA\Property(property: 'area_of_effect', type: 'string', nullable: true),
        new OA\Property(property: 'damage_type', type: 'string', nullable: true),
        new OA\Property(property: 'damage', type: 'double', nullable: true),
    ],
    type: 'object'
)]
class GrenadeResource extends AbstractBaseResource
{
    public static function validIncludes(): array
    {
        return [
            'damages',
            'ports',
            'shops',
            'shops.items',
        ];
    }

    public function toArray(Request $request): array
    {
        return [
            'area_of_effect' => $this->aoe,
            'damage_type' => $this->damage_type,
            'damage' => $this->damage,
        ];
    }
}
