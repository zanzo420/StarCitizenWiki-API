<?php

declare(strict_types=1);

namespace App\Http\Resources\SC\Item;

use App\Http\Resources\AbstractBaseResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'item_port_data_v2',
    title: 'Item Port Data',
    properties: [
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'display_name', type: 'string', nullable: true),
        new OA\Property(property: 'position', type: 'string', nullable: true),
        new OA\Property(property: 'sizes', properties: [
            new OA\Property(property: 'min', type: 'integer', nullable: true),
            new OA\Property(property: 'max', type: 'integer', nullable: true),
        ], type: 'object'),
        new OA\Property(property: 'compatible_types', ref: '#/components/schemas/item_port_type_v2', nullable: true),
        new OA\Property(
            property: 'tags',
            type: 'array',
            items: new OA\Items(type: 'string'),
            nullable: true,
        ),
        new OA\Property(
            property: 'required_tags',
            type: 'array',
            items: new OA\Items(type: 'string'),
            nullable: true,
        ),
        new OA\Property(property: 'equipped_item', ref: '#/components/schemas/item_v2', nullable: true),
    ],
    type: 'object'
)]
class ItemPortResource extends AbstractBaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'display_name' => $this->display_name,
            'position' => $this->position,
            'sizes' => [
                'min' => $this->min_size,
                'max' => $this->max_size,
            ],
            'compatible_types' => ItemPortTypeResource::collection($this->compatibleTypes),
            'tags' => $this->defaultTags->pluck('name')->toArray(),
            'required_tags' => $this->requiredTags->pluck('name')->toArray(),
            'equipped_item' => new ItemLinkResource($this->item),
        ];
    }
}
