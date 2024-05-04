<?php

declare(strict_types=1);

namespace App\Http\Resources\SC\Char\PersonalWeapon;

use App\Http\Resources\AbstractBaseResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'barrel_attach_v2',
    title: 'Barrel Attach',
    description: 'Suppressors, Laser Pointer, etc.',
    properties: [
        new OA\Property(property: 'type', type: 'string', nullable: true),
    ],
    type: 'object'
)]
class BarrelAttachResource extends AbstractBaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->type ?? null,
        ];
    }
}
