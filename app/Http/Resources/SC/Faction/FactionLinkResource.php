<?php

namespace App\Http\Resources\SC\Faction;

use App\Http\Resources\AbstractBaseResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'faction_link_v2',
    title: 'Faction Link',
    properties: [
        new OA\Property(property: 'uuid', type: 'string'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'link', type: 'string'),
    ],
    type: 'object'
)]
class FactionLinkResource extends AbstractBaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'link' => $this->makeApiUrl(self::FACTIONS_SHOW, $this->uuid),
        ];
    }
}
