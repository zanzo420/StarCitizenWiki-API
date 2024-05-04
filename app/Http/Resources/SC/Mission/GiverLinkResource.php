<?php

namespace App\Http\Resources\SC\Mission;

use App\Http\Resources\AbstractTranslationResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'mission_giver_link_v2',
    title: 'Mission Giver Link',
    properties: [
        new OA\Property(property: 'uuid', type: 'string', nullable: true),
        new OA\Property(property: 'name', type: 'string', nullable: true),
        new OA\Property(property: 'link', type: 'string', nullable: true),
    ],
    type: 'object'
)]
class GiverLinkResource extends AbstractTranslationResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'link' => $this->makeApiUrl(self::MISSION_GIVERS_SHOW, $this->uuid),
        ];
    }
}
