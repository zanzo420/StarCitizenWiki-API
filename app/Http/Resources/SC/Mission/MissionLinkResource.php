<?php

namespace App\Http\Resources\SC\Mission;

use App\Http\Resources\AbstractBaseResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'mission_link_v2',
    title: 'Mission Link',
    properties: [
        new OA\Property(property: 'uuid', type: 'string', nullable: true),
        new OA\Property(property: 'title', type: 'string', nullable: true),
        new OA\Property(property: 'link', type: 'string'),
    ],
    type: 'object'
)]
class MissionLinkResource extends AbstractBaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->titleHUD ?? $this->title,
            'link' => $this->makeApiUrl(self::MISSIONS_SHOW, $this->uuid),
        ];
    }
}
