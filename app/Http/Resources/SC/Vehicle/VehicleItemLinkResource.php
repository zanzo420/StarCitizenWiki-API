<?php

declare(strict_types=1);

namespace App\Http\Resources\SC\Vehicle;

use App\Http\Resources\AbstractBaseResource;
use App\Http\Resources\SC\Manufacturer\ManufacturerLinkResource;
use App\Http\Resources\SC\Shop\ShopResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'vehicle_item_link_v2',
    title: 'Vehicle Item Link',
    description: 'Link to a vehicle item',
    type: 'object',
    allOf: [
        new OA\Schema(
            properties: [
                new OA\Property(property: 'uuid', type: 'string'),
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'type', type: 'string', nullable: true),
                new OA\Property(property: 'grade', type: 'string', nullable: true),
                new OA\Property(property: 'class', type: 'string', nullable: true),
                new OA\Property(property: 'manufacturer', ref: '#/components/schemas/manufacturer_link_v2'),
                new OA\Property(property: 'link', type: 'string'),
            ],
            type: 'object'
        ),
        new OA\Schema(ref: '#/components/schemas/metadata_v2'),
    ]
)]
class VehicleItemLinkResource extends AbstractBaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'type' => $this->type,
            'grade' => $this->vehicleItem->grade,
            'class' => $this->vehicleItem->class,
            'manufacturer' => new ManufacturerLinkResource($this->manufacturer),
            'link' => $this->makeApiUrl(self::ITEMS_SHOW, $this->uuid),
            'shops' => ShopResource::collection($this->whenLoaded('shops')),
            'updated_at' => $this->updated_at,
            'version' => $this->version,
        ];
    }
}
