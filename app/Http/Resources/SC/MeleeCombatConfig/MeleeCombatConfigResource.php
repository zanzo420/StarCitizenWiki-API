<?php

declare(strict_types=1);

namespace App\Http\Resources\SC\MeleeCombatConfig;

use App\Http\Resources\AbstractBaseResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'melee_combat_config_v2',
    title: 'Melee Combat Config',
    description: 'Melee Combat Config',
    properties: [
        new OA\Property(property: 'category', type: 'string', nullable: true),
        new OA\Property(property: 'damage', type: 'double', nullable: true),
        new OA\Property(property: 'stun_recovery_modifier', type: 'double', nullable: true),
        new OA\Property(property: 'block_stun_reduction_modifier', type: 'double', nullable: true),
        new OA\Property(property: 'block_stun_stamina_modifier', type: 'double', nullable: true),
        new OA\Property(property: 'attack_impulse', type: 'double', nullable: true),
        new OA\Property(property: 'ignore_body_part_impulse_scale', type: 'boolean', nullable: true),
        new OA\Property(property: 'fullbody_animation', type: 'boolean', nullable: true),
        new OA\Property(
            property: 'damages',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/melee_damage_v2'),
            nullable: true,
        ),
    ],
    type: 'object'
)]
class MeleeCombatConfigResource extends AbstractBaseResource
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
            'category' => $this->category,
            'damage' => $this->damage,
            'stun_recovery_modifier' => $this->stun_recovery_modifier,
            'block_stun_reduction_modifier' => $this->block_stun_reduction_modifier,
            'block_stun_stamina_modifier' => $this->block_stun_stamina_modifier,
            'attack_impulse' => $this->attack_impulse,
            'ignore_body_part_impulse_scale' => $this->ignore_body_part_impulse_scale,
            'fullbody_animation' => $this->fullbody_animation,
            'damages' => MeleeDamageResource::collection($this->damages),
        ];
    }
}
