<?php

namespace App\Http\Resources\SC\Mission;

use App\Http\Resources\AbstractTranslationResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'mission_giver_v2',
    title: 'Mission Giver',
    properties: [
        new OA\Property(property: 'uuid', type: 'string', nullable: true),
        new OA\Property(property: 'name', type: 'string', nullable: true),
        new OA\Property(property: 'headquarters', type: 'string', nullable: true),
        new OA\Property(property: 'invitation_timeout', type: 'double', nullable: true),
        new OA\Property(property: 'visit_timeout', type: 'double', nullable: true),
        new OA\Property(property: 'short_cooldown', type: 'double', nullable: true),
        new OA\Property(property: 'medium_cooldown', type: 'double', nullable: true),
        new OA\Property(property: 'long_cooldown', type: 'double', nullable: true),
        new OA\Property(
            property: 'description',
            oneOf: [
                new OA\Schema(type: 'string'),
                new OA\Schema(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/translation_v2'),
                ),
            ],
        ),
    ],
    type: 'object'
)]
class GiverResource extends AbstractTranslationResource
{
    public static function validIncludes(): array
    {
        return [
            'missions',
        ];
    }

    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'headquarters' => $this->headquarters,
            'invitation_timeout' => $this->invitation_timeout,
            'visit_timeout' => $this->visit_timeout,
            'short_cooldown' => $this->short_cooldown,
            'medium_cooldown' => $this->medium_cooldown,
            'long_cooldown' => $this->long_cooldown,
            'description' => $this->getTranslation($this, $request),
            'missions' => $this->whenLoaded('missions', fn () => MissionLinkResource::collection($this->missions)),
        ];
    }
}
