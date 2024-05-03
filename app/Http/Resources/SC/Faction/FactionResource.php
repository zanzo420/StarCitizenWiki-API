<?php

namespace App\Http\Resources\SC\Faction;

use App\Http\Resources\AbstractTranslationResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'faction_v2',
    title: 'Faction',
    description: 'A Faction in Star Citizen',
    properties: [
        new OA\Property(property: 'uuid', type: 'string'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'game_token', type: 'string'),
        new OA\Property(property: 'class_name', type: 'string'),
        new OA\Property(property: 'default_reaction', type: 'string', nullable: true),
        new OA\Property(
            property: 'relations',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/faction_relation_v2'),
            nullable: true,
        ),
    ],
    type: 'object'
)]
class FactionResource extends AbstractTranslationResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'description' => $this->description,
            'class_name' => $this->class_name,
            'game_token' => $this->game_token,
            'default_reaction' => $this->default_reaction,
            'relations' => FactionRelationResource::collection($this->relations),
        ];
    }
}
