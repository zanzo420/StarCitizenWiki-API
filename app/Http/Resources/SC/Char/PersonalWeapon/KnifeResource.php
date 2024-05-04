<?php

declare(strict_types=1);

namespace App\Http\Resources\SC\Char\PersonalWeapon;

use App\Http\Resources\AbstractBaseResource;
use App\Http\Resources\SC\MeleeCombatConfig\MeleeCombatConfigResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'knife_v2',
    title: 'Knife',
    description: 'Knifes',
    properties: [
        new OA\Property(property: 'can_be_used_for_take_down', type: 'boolean', nullable: true),
        new OA\Property(property: 'can_block', type: 'boolean', nullable: true),
        new OA\Property(property: 'can_be_used_in_prone', type: 'boolean', nullable: true),
        new OA\Property(property: 'can_dodge', type: 'boolean', nullable: true),
        new OA\Property(
            property: 'attack_modes',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/melee_combat_config_v2'),
            nullable: true,
        ),
    ],
    type: 'object'
)]
class KnifeResource extends AbstractBaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'can_be_used_for_take_down' => $this->can_be_used_for_take_down,
            'can_block' => $this->can_block,
            'can_be_used_in_prone' => $this->can_be_used_in_prone,
            'can_dodge' => $this->can_dodge,
            'attack_modes' => MeleeCombatConfigResource::collection($this->combatConfig),
        ];
    }
}
