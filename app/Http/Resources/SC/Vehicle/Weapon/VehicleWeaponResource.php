<?php

declare(strict_types=1);

namespace App\Http\Resources\SC\Vehicle\Weapon;

use App\Http\Resources\AbstractBaseResource;
use App\Http\Resources\SC\Ammunition\AmmunitionResource;
use App\Http\Resources\SC\Weapon\WeaponDamageResource;
use App\Http\Resources\SC\Weapon\WeaponModeResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'vehicle_weapon_v2',
    title: 'Vehicle Weapon',
    description: 'A weapon usable on a vehicle',
    type: 'object',
    allOf: [
        new OA\Schema(ref: '#/components/schemas/item_base_v2'),
        new OA\Schema(ref: '#/components/schemas/vehicle_weapon_specification_v2'),
    ],
)]

#[OA\Schema(
    schema: 'vehicle_weapon_specification_v2',
    title: 'Vehicle Weapon',
    properties: [
        new OA\Property(property: 'class', type: 'string', nullable: true),
        new OA\Property(property: 'capacity', type: 'double', nullable: true),
        new OA\Property(property: 'range', type: 'string', nullable: true),
        new OA\Property(property: 'damage_per_shot', type: 'double', nullable: true),
        new OA\Property(
            property: 'modes',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/weapon_mode_v2'),
            nullable: true
        ),
        new OA\Property(
            property: 'damages',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/weapon_damage_v2'),
            nullable: true
        ),
        new OA\Property(
            property: 'regeneration',
            ref: '#/components/schemas/vehicle_weapon_regen_v2',
            nullable: true
        ),
        new OA\Property(property: 'ammunition', ref: '#/components/schemas/ammunition_v2', nullable: true),
    ],
    type: 'object'
)]
class VehicleWeaponResource extends AbstractBaseResource
{
    public static function validIncludes(): array
    {
        return [
            'modes',
            'damages',
            'ports',
            'shops',
            'shops.items',
        ];
    }

    public function toArray(Request $request): array
    {
        return [
            'class' => $this->weapon_class,
            'type' => $this->weapon_type,
            'capacity' => $this->capacity ?? null,
            'range' => $this->ammunition->range ?? null,
            'damage_per_shot' => $this->ammunition->damage ?? null,
            'modes' => WeaponModeResource::collection($this->whenLoaded('modes')),
            'damages' => WeaponDamageResource::collection($this->damages()),
            'regeneration' => new VehicleWeaponRegenResource($this->whenLoaded('regen')),
            'ammunition' => new AmmunitionResource($this->ammunition),
        ];
    }
}
