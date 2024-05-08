<?php

namespace App\Http\Resources\SC\Faction;

use App\Http\Resources\AbstractBaseResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'faction_relation_v2',
    title: 'Faction Relation',
    description: 'Relation between two factions',
    type: 'object',
    allOf: [
        new OA\Schema(ref: '#/components/schemas/faction_link_v2'),
        new OA\Schema(
            properties: [
                new OA\Property(property: 'relation', type: 'string'),
            ],
            type: 'object'
        ),
    ]
)]
class FactionRelationResource extends AbstractBaseResource
{
    public function toArray(Request $request): array
    {
        return (new FactionLinkResource($this->faction))->resolve($request) + [
            'relation' => $this->relation,
        ];
    }
}
