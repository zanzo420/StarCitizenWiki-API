<?php

namespace App\Http\Resources\SC\Faction;

use App\Http\Resources\AbstractBaseResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'faction_relation_v2',
    title: 'Faction Relation',
    description: 'Relation between two factions',
    properties: [
        new OA\Property(property: 'uuid', type: 'string'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'relation', type: 'string'),
        new OA\Property(property: 'link', type: 'string'),
    ],
    type: 'object'
)]
class FactionRelationResource extends AbstractBaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->faction->uuid,
            'name' => $this->faction->name,
            'relation' => $this->relation,
            'link' => $this->makeApiUrl(self::FACTIONS_SHOW, $this->faction->uuid),
        ];
    }
}
