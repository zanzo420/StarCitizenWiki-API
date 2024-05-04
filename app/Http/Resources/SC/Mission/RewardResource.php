<?php

namespace App\Http\Resources\SC\Mission;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'mission_reward_v2',
    title: 'Mission Rewards',
    properties: [
        new OA\Property(property: 'amount', type: 'double', nullable: true),
        new OA\Property(property: 'max', type: 'double', nullable: true),
        new OA\Property(property: 'plus_bonuses', type: 'boolean'),
        new OA\Property(property: 'currency', type: 'string'),
        new OA\Property(property: 'reputation_bonus', type: 'string'),
    ],
    type: 'object'
)]
class RewardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'amount' => $this->amount,
            'max' => $this->max,
            'plus_bonuses' => $this->plus_bonuses,
            'currency' => $this->currency,
            'reputation_bonus' => $this->reputation_bonus,
        ];
    }
}
